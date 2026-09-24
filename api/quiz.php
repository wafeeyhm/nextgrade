<?php
// NextGrade API - 10-Question Quiz Sessions & Scoring Engine
require_once __DIR__ . '/../db.php';

header('Content-Type: application/json; charset=utf-8');
session_start();

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $topicId = $_GET['topic_id'] ?? null;
    $subjectId = $_GET['subject_id'] ?? null;
    $limit = 10; // Requirement 1.10: 10 questions per session

    try {
        $questions = [];

        if ($topicId) {
            // First fetch from specific topic
            $stmt = $pdo->prepare("SELECT * FROM questions WHERE topic_id = ? ORDER BY RAND() LIMIT ?");
            $stmt->bindValue(1, $topicId, PDO::PARAM_STR);
            $stmt->bindValue(2, $limit, PDO::PARAM_INT);
            $stmt->execute();
            $questions = $stmt->fetchAll();

            // If topic has fewer than 10 questions, pull complementary questions from same subject
            if (count($questions) < $limit) {
                $needed = $limit - count($questions);
                $existingIds = array_column($questions, 'id');
                $placeholders = count($existingIds) > 0 ? implode(',', array_fill(0, count($existingIds), '?')) : '0';

                // Find subject_id of the topic
                $stmtTopic = $pdo->prepare("SELECT subject_id FROM topics WHERE id = ?");
                $stmtTopic->execute([$topicId]);
                $tRow = $stmtTopic->fetch();
                $sId = $tRow['subject_id'] ?? null;

                if ($sId) {
                    $sql = "SELECT * FROM questions WHERE subject_id = ? AND id NOT IN ($placeholders) ORDER BY RAND() LIMIT ?";
                    $stmtExtra = $pdo->prepare($sql);
                    $paramIdx = 1;
                    $stmtExtra->bindValue($paramIdx++, $sId, PDO::PARAM_STR);
                    foreach ($existingIds as $eId) {
                        $stmtExtra->bindValue($paramIdx++, $eId, PDO::PARAM_INT);
                    }
                    $stmtExtra->bindValue($paramIdx++, $needed, PDO::PARAM_INT);
                    $stmtExtra->execute();
                    $extraQuestions = $stmtExtra->fetchAll();
                    $questions = array_merge($questions, $extraQuestions);
                }
            }
        } elseif ($subjectId) {
            // 10 questions across selected subject
            $stmt = $pdo->prepare("SELECT * FROM questions WHERE subject_id = ? ORDER BY RAND() LIMIT ?");
            $stmt->bindValue(1, $subjectId, PDO::PARAM_STR);
            $stmt->bindValue(2, $limit, PDO::PARAM_INT);
            $stmt->execute();
            $questions = $stmt->fetchAll();
        } else {
            // 10 mixed questions across all subjects
            $stmt = $pdo->prepare("SELECT * FROM questions ORDER BY RAND() LIMIT ?");
            $stmt->bindValue(1, $limit, PDO::PARAM_INT);
            $stmt->execute();
            $questions = $stmt->fetchAll();
        }

        // Clean & format questions
        $formatted = [];
        foreach ($questions as $q) {
            $options = json_decode($q['options_json'], true) ?? [];
            
            // Normalize image path with BASE_URL
            $imgUrl = null;
            if (!empty($q['image_url'])) {
                $cleanPath = ltrim($q['image_url'], '/');
                if (str_starts_with($cleanPath, 'nextgrade/')) {
                    $cleanPath = substr($cleanPath, strlen('nextgrade/'));
                }
                $imgUrl = BASE_URL . $cleanPath;
            }

            // Parse correct answer
            $rawCorrect = $q['correct_answer'];
            $decodedCorrect = json_decode($rawCorrect, true);
            $parsedCorrect = ($decodedCorrect !== null) ? $decodedCorrect : $rawCorrect;

            $formatted[] = [
                'id' => (int)$q['id'],
                'topic_id' => $q['topic_id'],
                'subject_id' => $q['subject_id'],
                'question_text' => $q['question_text'],
                'question_audio' => $q['question_audio'],
                'lang' => $q['lang'] ?? 'en',
                'question_type' => $q['question_type'],
                'image_url' => $imgUrl,
                'passage' => $q['passage'],
                'options' => $options,
                'correct_answer' => $parsedCorrect,
                'hint_text' => $q['hint_text'],
                'hint_audio' => $q['hint_audio'],
                'meta_data' => json_decode($q['meta_data_json'] ?? '', true)
            ];
        }

        echo json_encode([
            'success' => true,
            'total' => count($formatted),
            'questions' => $formatted
        ]);
        exit;

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}

if ($method === 'POST') {
    // Record completed quiz session
    $rawInput = file_get_contents('php://input');
    $payload = json_decode($rawInput, true);

    if (!$payload) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON payload']);
        exit;
    }

    $studentName = trim($payload['student_name'] ?? $_SESSION['student_name'] ?? 'Friend');
    $studentId = $payload['student_id'] ?? $_SESSION['student_id'] ?? null;
    $subjectId = $payload['subject_id'] ?? null;
    $topicId = $payload['topic_id'] ?? null;
    $totalQuestions = (int)($payload['total_questions'] ?? 10);
    $timeSpent = (int)($payload['time_spent_seconds'] ?? 0);
    $answers = $payload['answers'] ?? [];

    // Calculate score
    $correctCount = 0;
    foreach ($answers as $ans) {
        if (!empty($ans['is_correct'])) {
            $correctCount++;
        }
    }

    $percentage = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100, 2) : 0.00;

    try {
        $pdo->beginTransaction();

        // 1. Insert session
        $stmtSession = $pdo->prepare("
            INSERT INTO quiz_sessions 
            (student_id, student_name, subject_id, topic_id, total_questions, score, percentage, time_spent_seconds, completed_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        $stmtSession->execute([
            $studentId,
            $studentName,
            $subjectId,
            $topicId,
            $totalQuestions,
            $correctCount,
            $percentage,
            $timeSpent
        ]);
        $sessionId = (int)$pdo->lastInsertId();

        // 2. Insert answer breakdown
        if (!empty($answers) && is_array($answers)) {
            $stmtAns = $pdo->prepare("
                INSERT INTO quiz_session_answers 
                (session_id, question_id, student_answer, is_correct, used_hint, created_at)
                VALUES (?, ?, ?, ?, ?, NOW())
            ");

            foreach ($answers as $ans) {
                $qId = (int)($ans['question_id'] ?? 0);
                $stAns = is_array($ans['student_answer'] ?? null) 
                    ? json_encode($ans['student_answer'], JSON_UNESCAPED_UNICODE) 
                    : (string)($ans['student_answer'] ?? '');
                $isCorrect = !empty($ans['is_correct']) ? 1 : 0;
                $usedHint = !empty($ans['used_hint']) ? 1 : 0;

                if ($qId > 0) {
                    $stmtAns->execute([$sessionId, $qId, $stAns, $isCorrect, $usedHint]);
                }
            }
        }

        $pdo->commit();

        // Stars & celebratory badge
        $stars = 1;
        $badge = 'Good Try! 🌟';
        $message = 'Keep practicing, you are getting smarter every day!';
        if ($percentage >= 90) {
            $stars = 3;
            $badge = 'Superstar Genius! 🏆⭐⭐⭐';
            $message = 'Spectacular job! You scored almost perfect!';
        } elseif ($percentage >= 70) {
            $stars = 2;
            $badge = 'Great Champion! ⭐⭐';
            $message = 'Wonderful effort! You did really well!';
        }

        echo json_encode([
            'success' => true,
            'session_id' => $sessionId,
            'student_name' => $studentName,
            'score' => $correctCount,
            'total' => $totalQuestions,
            'percentage' => $percentage,
            'stars' => $stars,
            'badge' => $badge,
            'message' => $message,
            'time_spent_seconds' => $timeSpent
        ]);
        exit;

    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
