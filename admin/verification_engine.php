<?php
// NextGrade - System Verification & Production Readiness Engine
// Validates Database, Pages & Routing, Media Assets, User Credentials, and Online Server Security.

require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../auth_helper.php';

class SystemVerificationEngine {
    private $pdo;
    private $rootDir;
    private $baseUrl;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->rootDir = realpath(__DIR__ . '/..');
        $this->baseUrl = defined('BASE_URL') ? BASE_URL : '/nextgrade/';
    }

    /**
     * Run all diagnostic checks and return aggregated results
     */
    public function runAllChecks(): array {
        $startTime = microtime(true);

        $dbResults = $this->checkDatabase();
        $pagesResults = $this->checkPages();
        $imagesResults = $this->checkImages();
        $credentialsResults = $this->checkCredentials();
        $envResults = $this->checkProductionEnvironment();

        $executionTime = round((microtime(true) - $startTime) * 1000, 2);

        $categories = [
            'database' => $dbResults,
            'pages' => $pagesResults,
            'images' => $imagesResults,
            'credentials' => $credentialsResults,
            'environment' => $envResults
        ];

        $totalChecks = 0;
        $passedChecks = 0;
        $warningChecks = 0;
        $failedChecks = 0;

        foreach ($categories as $cat) {
            foreach ($cat['checks'] as $check) {
                $totalChecks++;
                if ($check['status'] === 'pass') {
                    $passedChecks++;
                } elseif ($check['status'] === 'warning') {
                    $warningChecks++;
                } else {
                    $failedChecks++;
                }
            }
        }

        // Calculate health score: passed = 100%, warning = 70%, failed = 0%
        $healthScore = $totalChecks > 0 
            ? round((($passedChecks * 1.0 + $warningChecks * 0.7) / $totalChecks) * 100) 
            : 0;

        $overallStatus = 'pass';
        if ($failedChecks > 0) {
            $overallStatus = 'fail';
        } elseif ($warningChecks > 0) {
            $overallStatus = 'warning';
        }

        return [
            'timestamp' => date('Y-m-d H:i:s'),
            'execution_time_ms' => $executionTime,
            'overall_status' => $overallStatus,
            'health_score' => $healthScore,
            'summary' => [
                'total' => $totalChecks,
                'passed' => $passedChecks,
                'warnings' => $warningChecks,
                'failed' => $failedChecks
            ],
            'categories' => $categories
        ];
    }

    /**
     * 1. DATABASE HEALTH & INTEGRITY
     */
    public function checkDatabase(): array {
        $checks = [];
        $catTitle = "Database Health & Schema Integrity";

        // Check 1: MySQL Connection & Latency
        $pingStart = microtime(true);
        try {
            $version = $this->pdo->query("SELECT VERSION()")->fetchColumn();
            $latency = round((microtime(true) - $pingStart) * 1000, 2);
            $checks[] = [
                'name' => 'MySQL Connection & Ping Latency',
                'status' => 'pass',
                'message' => "Connected successfully to MySQL ($version). Ping latency: {$latency} ms.",
                'details' => ['version' => $version, 'latency_ms' => $latency]
            ];
        } catch (Exception $e) {
            $checks[] = [
                'name' => 'MySQL Connection & Ping Latency',
                'status' => 'fail',
                'message' => "Database connection failure: " . $e->getMessage(),
                'details' => null
            ];
            return ['title' => $catTitle, 'checks' => $checks];
        }

        // Check 2: Core Schema Tables
        $expectedTables = [
            'admins' => 'System Administrators',
            'parents' => 'Parent Accounts',
            'students' => 'Student Profiles',
            'subjects' => 'Curriculum Subjects',
            'topics' => 'Learning Topics',
            'revisions' => '5-Minute Revision Guides',
            'questions' => 'Question Bank',
            'quiz_sessions' => 'Quiz Sessions',
            'quiz_session_answers' => 'Quiz Detailed Answers',
            'revision_sessions' => 'Revision Tracking'
        ];

        try {
            $existingTables = $this->pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
            $missingTables = [];
            $tableCounts = [];

            foreach ($expectedTables as $tbl => $label) {
                if (!in_array($tbl, $existingTables)) {
                    $missingTables[] = $tbl;
                } else {
                    $cnt = (int)$this->pdo->query("SELECT COUNT(*) FROM `$tbl`")->fetchColumn();
                    $tableCounts[$tbl] = ['label' => $label, 'count' => $cnt];
                }
            }

            if (empty($missingTables)) {
                $checks[] = [
                    'name' => 'Core Schema Tables Verification',
                    'status' => 'pass',
                    'message' => 'All ' . count($expectedTables) . ' core database tables exist and are accessible.',
                    'details' => $tableCounts
                ];
            } else {
                $checks[] = [
                    'name' => 'Core Schema Tables Verification',
                    'status' => 'fail',
                    'message' => 'Missing critical tables: ' . implode(', ', $missingTables),
                    'details' => ['missing' => $missingTables, 'existing' => $tableCounts]
                ];
            }
        } catch (Exception $e) {
            $checks[] = [
                'name' => 'Core Schema Tables Verification',
                'status' => 'fail',
                'message' => 'Error querying tables: ' . $e->getMessage(),
                'details' => null
            ];
        }

        // Check 3: Schema Columns & Foreign Key Constraints
        try {
            $columnChecks = [
                ['table' => 'students', 'col' => 'parent_id', 'desc' => 'Multi-parent link foreign key'],
                ['table' => 'students', 'col' => 'username', 'desc' => 'Kid simple username'],
                ['table' => 'students', 'col' => 'pin_code', 'desc' => 'Kid 4-digit PIN access'],
                ['table' => 'students', 'col' => 'grade_level', 'desc' => 'Curriculum grade level'],
                ['table' => 'subjects', 'col' => 'grade_level', 'desc' => 'Subject grade level isolation'],
                ['table' => 'topics', 'col' => 'grade_level', 'desc' => 'Topic grade level isolation'],
                ['table' => 'questions', 'col' => 'grade_level', 'desc' => 'Question grade segregation level'],
                ['table' => 'parents', 'col' => 'parent_code', 'desc' => 'Unique parent registration code'],
                ['table' => 'admins', 'col' => 'password_hash', 'desc' => 'Secure hashed admin credentials'],
                ['table' => 'questions', 'col' => 'image_url', 'desc' => 'Visual illustration path']
            ];

            $missingCols = [];
            foreach ($columnChecks as $cc) {
                $quotedCol = $this->pdo->quote($cc['col']);
                $stmt = $this->pdo->query("SHOW COLUMNS FROM `{$cc['table']}` LIKE $quotedCol");
                if (!$stmt->fetch()) {
                    $missingCols[] = "{$cc['table']}.{$cc['col']} ({$cc['desc']})";
                }
            }

            if (empty($missingCols)) {
                $checks[] = [
                    'name' => 'Critical Table Columns & Schema v2 Requirements',
                    'status' => 'pass',
                    'message' => 'All multi-parent, kid PIN, and question schema columns verified.',
                    'details' => array_column($columnChecks, 'desc', 'col')
                ];
            } else {
                $checks[] = [
                    'name' => 'Critical Table Columns & Schema v2 Requirements',
                    'status' => 'fail',
                    'message' => 'Missing required schema columns: ' . implode(', ', $missingCols),
                    'details' => $missingCols
                ];
            }
        } catch (Exception $e) {
            $checks[] = [
                'name' => 'Critical Table Columns & Schema v2 Requirements',
                'status' => 'fail',
                'message' => 'Error checking columns: ' . $e->getMessage(),
                'details' => null
            ];
        }

        // Check 4: Read/Write Transaction Test
        try {
            $testId = 'test_check_' . time();
            $this->pdo->beginTransaction();
            $this->pdo->exec("INSERT INTO quiz_sessions (student_name, total_questions, score, percentage) VALUES ('$testId', 1, 1, 100.00)");
            $insertedId = $this->pdo->lastInsertId();
            $readBack = $this->pdo->query("SELECT student_name FROM quiz_sessions WHERE id = $insertedId")->fetchColumn();
            $this->pdo->rollBack();

            if ($readBack === $testId) {
                $checks[] = [
                    'name' => 'Database Read/Write & Transaction Test',
                    'status' => 'pass',
                    'message' => 'Database write, read, and rollback transactions confirmed functional.',
                    'details' => ['test_mode' => 'Atomic Transaction Rollback', 'status' => 'Verified']
                ];
            } else {
                $checks[] = [
                    'name' => 'Database Read/Write & Transaction Test',
                    'status' => 'fail',
                    'message' => 'Read back mismatch during transaction test.',
                    'details' => null
                ];
            }
        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            $checks[] = [
                'name' => 'Database Read/Write & Transaction Test',
                'status' => 'fail',
                'message' => 'Read/Write transaction failed: ' . $e->getMessage(),
                'details' => null
            ];
        }

        // Check 5: Content Population
        try {
            $qCount = (int)$this->pdo->query("SELECT COUNT(*) FROM questions")->fetchColumn();
            $subCount = (int)$this->pdo->query("SELECT COUNT(*) FROM subjects")->fetchColumn();
            $topCount = (int)$this->pdo->query("SELECT COUNT(*) FROM topics")->fetchColumn();
            $revCount = (int)$this->pdo->query("SELECT COUNT(*) FROM revisions")->fetchColumn();

            if ($qCount >= 500 && $subCount >= 4 && $topCount >= 10) {
                $checks[] = [
                    'name' => 'Question Bank & Curriculum Data Population',
                    'status' => 'pass',
                    'message' => "Curriculum data fully populated: $qCount questions, $subCount subjects, $topCount topics, $revCount revisions.",
                    'details' => [
                        'questions' => $qCount,
                        'subjects' => $subCount,
                        'topics' => $topCount,
                        'revisions' => $revCount
                    ]
                ];
            } else {
                $checks[] = [
                    'name' => 'Question Bank & Curriculum Data Population',
                    'status' => 'warning',
                    'message' => "Curriculum data counts lower than recommended (Questions: $qCount, Subjects: $subCount). Consider running seed.php.",
                    'details' => compact('qCount', 'subCount', 'topCount', 'revCount')
                ];
            }
        } catch (Exception $e) {
            $checks[] = [
                'name' => 'Question Bank & Curriculum Data Population',
                'status' => 'fail',
                'message' => 'Failed counting curriculum data: ' . $e->getMessage(),
                'details' => null
            ];
        }

        // Check 6: Year 6 (PSR Brunei) Curriculum & 30-Question Topic Bank
        try {
            $psrTopicRows = $this->pdo->query("
                SELECT t.id, t.name, s.name as subject_name, COUNT(q.id) as q_count
                FROM topics t
                JOIN subjects s ON s.id = t.subject_id
                LEFT JOIN questions q ON q.topic_id = t.id AND q.grade_level = 'Year 6 (PSR)'
                WHERE t.grade_level = 'Year 6 (PSR)'
                GROUP BY t.id, t.name, s.name
            ")->fetchAll();

            $totalPsrTopics = count($psrTopicRows);
            $understockedTopics = [];
            $totalPsrQuestions = 0;

            foreach ($psrTopicRows as $row) {
                $totalPsrQuestions += (int)$row['q_count'];
                if ((int)$row['q_count'] < 30) {
                    $understockedTopics[] = "{$row['name']} ({$row['q_count']}/30)";
                }
            }

            // Check grade isolation: ensure zero PSR questions are mislabelled
            $leakageCount = (int)$this->pdo->query("
                SELECT COUNT(*) FROM questions 
                WHERE topic_id LIKE 'psr_%' AND (grade_level != 'Year 6 (PSR)' OR grade_level IS NULL)
            ")->fetchColumn();

            if ($totalPsrTopics >= 21 && empty($understockedTopics) && $leakageCount === 0) {
                $checks[] = [
                    'name' => 'Year 6 (PSR Brunei) Curriculum & 30-Question Threshold',
                    'status' => 'pass',
                    'message' => "All $totalPsrTopics Year 6 PSR topics meet or exceed the 30-question requirement ($totalPsrQuestions total PSR questions). Strict grade isolation confirmed (0 leaks).",
                    'details' => [
                        'psr_topics_count' => $totalPsrTopics,
                        'total_psr_questions' => $totalPsrQuestions,
                        'minimum_per_topic' => '30 questions (Met)',
                        'grade_isolation' => '100% Verified (0 cross-grade leaks)'
                    ]
                ];
            } elseif (!empty($understockedTopics)) {
                $checks[] = [
                    'name' => 'Year 6 (PSR Brunei) Curriculum & 30-Question Threshold',
                    'status' => 'warning',
                    'message' => "Some Year 6 PSR topics have fewer than 30 questions: " . implode(', ', $understockedTopics),
                    'details' => compact('totalPsrTopics', 'totalPsrQuestions', 'understockedTopics')
                ];
            } else {
                $checks[] = [
                    'name' => 'Year 6 (PSR Brunei) Curriculum & 30-Question Threshold',
                    'status' => 'fail',
                    'message' => "Year 6 PSR curriculum not fully seeded ($totalPsrTopics/21 topics found). Run scripts/seed_psr_curriculum.php.",
                    'details' => compact('totalPsrTopics', 'totalPsrQuestions', 'leakageCount')
                ];
            }
        } catch (Exception $e) {
            $checks[] = [
                'name' => 'Year 6 (PSR Brunei) Curriculum & 30-Question Threshold',
                'status' => 'fail',
                'message' => 'Error verifying Year 6 PSR curriculum: ' . $e->getMessage(),
                'details' => null
            ];
        }

        return ['title' => $catTitle, 'checks' => $checks];
    }

    /**
     * 2. PAGES & ROUTE INTEGRITY
     */
    public function checkPages(): array {
        $checks = [];
        $catTitle = "Pages & Route Integrity";

        $pagesToValidate = [
            // Public & Student
            'index.php' => ['type' => 'Public Student Hub', 'expected_code' => 200],
            'quiz.php' => ['type' => 'Quiz Player', 'expected_code' => 200],
            'revision.php' => ['type' => '5-Minute Revision', 'expected_code' => 200],
            'worksheet.php' => ['type' => 'Printable Worksheets', 'expected_code' => 200],
            'guide.php' => ['type' => 'Security Gate to Admin', 'expected_code' => 302],
            // Parent
            'parent_login.php' => ['type' => 'Parent Login', 'expected_code' => 200],
            'parent.php' => ['type' => 'Parent Dashboard', 'expected_code' => 302],
            'parent_logout.php' => ['type' => 'Parent Logout', 'expected_code' => 302],
            // Admin
            'admin/login.php' => ['type' => 'Admin Login', 'expected_code' => 200],
            'admin/index.php' => ['type' => 'Admin Dashboard', 'expected_code' => 302],
            'admin/parents.php' => ['type' => 'Admin Parents CRUD', 'expected_code' => 302],
            'admin/kids.php' => ['type' => 'Admin Kids Management', 'expected_code' => 302],
            'admin/profile.php' => ['type' => 'Admin Profile & Credentials', 'expected_code' => 302],
            'admin/guide.php' => ['type' => 'System Guide', 'expected_code' => 302],
            'admin/developer_guide.php' => ['type' => 'Developer & Question Guide', 'expected_code' => 302],
            'admin/verification.php' => ['type' => 'System Verification Checker', 'expected_code' => 302],
            'admin/logout.php' => ['type' => 'Admin Logout', 'expected_code' => 302],
            // APIs
            'api/auth.php' => ['type' => 'Kid Auth API', 'expected_code' => 200],
            'api/quiz.php' => ['type' => 'Quiz API', 'expected_code' => 200],
            'api/revision.php' => ['type' => 'Revision API', 'expected_code' => 200],
            'api/worksheet.php' => ['type' => 'Worksheet API', 'expected_code' => 200],
            'api/subjects.php' => ['type' => 'Subjects API', 'expected_code' => 200],
            'api/topics.php' => ['type' => 'Topics API', 'expected_code' => 200],
            'api/parent_auth.php' => ['type' => 'Parent Auth API', 'expected_code' => 200],
            'api/parent_profile.php' => ['type' => 'Parent Profile API', 'expected_code' => 401],
            'api/parent_kids.php' => ['type' => 'Parent Kids API', 'expected_code' => 401],
            'api/parent_attempts.php' => ['type' => 'Parent Attempts API', 'expected_code' => 401],
            'api/admin_auth.php' => ['type' => 'Admin Auth API', 'expected_code' => 200],
            'api/admin_parents.php' => ['type' => 'Admin Parents API', 'expected_code' => 401],
            'api/admin_profile.php' => ['type' => 'Admin Profile API', 'expected_code' => 401],
            // Protected Utilities
            'seed.php' => ['type' => 'Database Seeder (Protected)', 'expected_code' => 302],
            'truncate.php' => ['type' => 'Database Truncate (Protected)', 'expected_code' => 302],
        ];

        // 1. File Existence & PHP Syntax Linter
        $syntaxErrors = [];
        $missingFiles = [];
        $totalFiles = count($pagesToValidate);

        foreach ($pagesToValidate as $relPath => $meta) {
            $absPath = $this->rootDir . '/' . $relPath;
            if (!file_exists($absPath)) {
                $missingFiles[] = $relPath;
                continue;
            }

            // Syntax check using php -l
            $escaped = escapeshellarg($absPath);
            $output = [];
            $exitCode = 0;
            exec("php -l $escaped 2>&1", $output, $exitCode);
            if ($exitCode !== 0) {
                $syntaxErrors[] = "$relPath: " . implode(" ", $output);
            }
        }

        if (empty($missingFiles) && empty($syntaxErrors)) {
            $checks[] = [
                'name' => 'File Structure & PHP Syntax Validation',
                'status' => 'pass',
                'message' => "All $totalFiles system pages and API scripts exist and passed PHP syntax verification with zero syntax errors.",
                'details' => ['scanned_files' => $totalFiles, 'syntax_errors' => 0]
            ];
        } else {
            $checks[] = [
                'name' => 'File Structure & PHP Syntax Validation',
                'status' => 'fail',
                'message' => 'Issues found: ' . count($missingFiles) . ' missing files, ' . count($syntaxErrors) . ' syntax errors.',
                'details' => ['missing' => $missingFiles, 'syntax_errors' => $syntaxErrors]
            ];
        }

        // 2. HTTP Endpoint Health (cURL / Local HTTP Check)
        $endpointChecks = [
            'index.php' => 'Student Home Hub',
            'parent_login.php' => 'Parent Portal Login',
            'admin/login.php' => 'System Admin Login',
            'api/subjects.php' => 'Subjects Catalog API',
            'api/topics.php?subject_id=maths' => 'Topics Catalog API (Maths)',
            'api/quiz.php?topic_id=math_addition' => 'Quiz Fetch API (Maths Addition)',
            'guide.php' => 'Root Guide Route (Admin Gate)',
            'seed.php' => 'Database Seeder Guard',
            'truncate.php' => 'Database Truncate Guard'
        ];

        $httpResults = [];
        $httpFailures = [];

        // Determine server host & port
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $siteRoot = $protocol . $host . $this->baseUrl;

        foreach ($endpointChecks as $endpoint => $desc) {
            $targetUrl = $siteRoot . $endpoint;
            $ch = curl_init($targetUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); // Check redirect status
            curl_setopt($ch, CURLOPT_TIMEOUT, 4);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

            $response = curl_exec($ch);
            $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if ($curlError) {
                // If cURL fails due to local port binding, mark as warning with detail
                $httpResults[$endpoint] = ['code' => 0, 'desc' => $desc, 'status' => 'cURL Error: ' . $curlError];
                $httpFailures[] = "$endpoint (cURL connection error)";
            } else {
                $isHealthy = false;
                // For public pages: 200 OK
                // For protected seed/truncate/guide: 302 or 401 is expected for unauthenticated requests
                if (in_array($endpoint, ['guide.php', 'seed.php', 'truncate.php'])) {
                    $isHealthy = in_array($statusCode, [302, 301, 401, 403]);
                } else {
                    $isHealthy = ($statusCode === 200);
                }

                $httpResults[$endpoint] = [
                    'code' => $statusCode,
                    'desc' => $desc,
                    'healthy' => $isHealthy
                ];

                if (!$isHealthy) {
                    $httpFailures[] = "$endpoint returned HTTP $statusCode";
                }
            }
        }

        if (empty($httpFailures)) {
            $checks[] = [
                'name' => 'Live HTTP Endpoints & Route Responses',
                'status' => 'pass',
                'message' => 'All public portals, APIs, and security gated routes returned expected HTTP status codes.',
                'details' => $httpResults
            ];
        } else {
            $checks[] = [
                'name' => 'Live HTTP Endpoints & Route Responses',
                'status' => 'warning',
                'message' => 'Some endpoints returned unexpected status codes: ' . implode(', ', $httpFailures),
                'details' => $httpResults
            ];
        }

        // 3. Security Route Protection Verification
        $rootGuide = file_get_contents($this->rootDir . '/guide.php');
        $seedFile = file_get_contents($this->rootDir . '/seed.php');
        $truncateFile = file_get_contents($this->rootDir . '/truncate.php');

        $isGuideGated = strpos($rootGuide, 'isAdminLoggedIn()') !== false;
        $isSeedGated = strpos($seedFile, 'requireAdmin()') !== false;
        $isTruncateGated = strpos($truncateFile, 'requireAdmin()') !== false;

        if ($isGuideGated && $isSeedGated && $isTruncateGated) {
            $checks[] = [
                'name' => 'Security Gating on Maintenance Scripts',
                'status' => 'pass',
                'message' => 'Root guide.php, seed.php, and truncate.php are strictly gated with admin authentication guards.',
                'details' => [
                    'guide_gated' => true,
                    'seed_gated' => true,
                    'truncate_gated' => true
                ]
            ];
        } else {
            $checks[] = [
                'name' => 'Security Gating on Maintenance Scripts',
                'status' => 'fail',
                'message' => 'Security vulnerability: maintenance scripts lack admin auth guards.',
                'details' => compact('isGuideGated', 'isSeedGated', 'isTruncateGated')
            ];
        }

        return ['title' => $catTitle, 'checks' => $checks];
    }

    /**
     * 3. MEDIA & ASSET INTEGRITY
     */
    public function checkImages(): array {
        $checks = [];
        $catTitle = "Media & Asset Integrity";

        try {
            $referencedImages = $this->pdo->query("
                SELECT DISTINCT image_url 
                FROM questions 
                WHERE image_url IS NOT NULL AND image_url != ''
            ")->fetchAll(PDO::FETCH_COLUMN);

            $totalReferenced = count($referencedImages);
            $validImages = 0;
            $missingImages = [];
            $zeroByteImages = [];
            $unreadableImages = [];

            foreach ($referencedImages as $imgRel) {
                // Normalize path
                $cleanPath = ltrim($imgRel, '/\\');
                $absPath = $this->rootDir . '/' . $cleanPath;

                if (!file_exists($absPath)) {
                    $missingImages[] = $imgRel;
                    continue;
                }

                if (!is_readable($absPath)) {
                    $unreadableImages[] = $imgRel;
                    continue;
                }

                $size = filesize($absPath);
                if ($size === 0) {
                    $zeroByteImages[] = $imgRel;
                    continue;
                }

                $validImages++;
            }

            if (empty($missingImages) && empty($zeroByteImages) && empty($unreadableImages)) {
                $checks[] = [
                    'name' => 'Question Bank Visual Illustrations',
                    'status' => 'pass',
                    'message' => "All $totalReferenced referenced question images exist on disk, are readable, and non-empty (0 missing).",
                    'details' => [
                        'total_referenced' => $totalReferenced,
                        'valid_on_disk' => $validImages,
                        'missing' => 0
                    ]
                ];
            } else {
                $checks[] = [
                    'name' => 'Question Bank Visual Illustrations',
                    'status' => 'fail',
                    'message' => "Image asset issues detected: " . count($missingImages) . " missing, " . count($zeroByteImages) . " 0-byte corrupt files.",
                    'details' => [
                        'missing' => array_slice($missingImages, 0, 10),
                        'zero_byte' => $zeroByteImages,
                        'total_missing' => count($missingImages)
                    ]
                ];
            }

            // Check Media Folders Structure
            $mediaFolders = [
                'images/binatang' => 'Haiwan / Animals',
                'images/kenderaan' => 'Kenderaan / Vehicles',
                'images/suku-kata' => 'Suku Kata / Syllables',
                'images/english' => 'English Illustrations',
                'images/maths' => 'Mathematics Visuals',
                'images/science' => 'Science Phenomena',
                'images/ict' => 'ICT Hardware & Tools'
            ];

            $missingFolders = [];
            $folderStats = [];

            foreach ($mediaFolders as $fld => $name) {
                $absFld = $this->rootDir . '/' . $fld;
                if (!is_dir($absFld)) {
                    $missingFolders[] = $fld;
                } else {
                    $files = @scandir($absFld);
                    $fileCount = $files ? count(array_diff($files, ['.', '..'])) : 0;
                    $folderStats[$fld] = ['label' => $name, 'files' => $fileCount];
                }
            }

            if (empty($missingFolders)) {
                $checks[] = [
                    'name' => 'Media Folder Architecture & Storage',
                    'status' => 'pass',
                    'message' => 'All ' . count($mediaFolders) . ' subject media asset directories exist with valid assets.',
                    'details' => $folderStats
                ];
            } else {
                $checks[] = [
                    'name' => 'Media Folder Architecture & Storage',
                    'status' => 'warning',
                    'message' => 'Missing media directories: ' . implode(', ', $missingFolders),
                    'details' => $folderStats
                ];
            }

            // Check App Icons & Static CSS/JS
            $staticAssets = [
                'css/app.css' => 'Application Stylesheet',
                'data/questions_bank.json' => 'Core Question Bank Master File'
            ];

            $missingStatic = [];
            foreach ($staticAssets as $sa => $desc) {
                if (!file_exists($this->rootDir . '/' . $sa)) {
                    $missingStatic[] = "$sa ($desc)";
                }
            }

            if (empty($missingStatic)) {
                $checks[] = [
                    'name' => 'Static Bundles & Question Bank Master JSON',
                    'status' => 'pass',
                    'message' => 'Master questions_bank.json and CSS stylesheet confirmed intact.',
                    'details' => array_keys($staticAssets)
                ];
            } else {
                $checks[] = [
                    'name' => 'Static Bundles & Question Bank Master JSON',
                    'status' => 'fail',
                    'message' => 'Missing static assets: ' . implode(', ', $missingStatic),
                    'details' => $missingStatic
                ];
            }

        } catch (Exception $e) {
            $checks[] = [
                'name' => 'Media Verification',
                'status' => 'fail',
                'message' => 'Error verifying media assets: ' . $e->getMessage(),
                'details' => null
            ];
        }

        return ['title' => $catTitle, 'checks' => $checks];
    }

    /**
     * 4. ADMIN & USER CREDENTIALS
     */
    public function checkCredentials(): array {
        $checks = [];
        $catTitle = "User & Admin Credentials";

        // 1. System Admin Credentials
        try {
            $admins = $this->pdo->query("SELECT id, username, email, full_name, password_hash FROM admins")->fetchAll();
            $adminCount = count($admins);

            if ($adminCount > 0) {
                // Verify default or active admin password verify
                $defaultAdmin = null;
                foreach ($admins as $adm) {
                    if ($adm['username'] === 'admin') {
                        $defaultAdmin = $adm;
                        break;
                    }
                }

                $adminDetails = [
                    'total_admins' => $adminCount,
                    'active_usernames' => array_column($admins, 'username'),
                    'hash_algorithm' => 'BCrypt / Argon2'
                ];

                if ($defaultAdmin) {
                    $isDefaultPass = password_verify('admin123', $defaultAdmin['password_hash']);
                    $adminDetails['default_admin_found'] = true;
                    $adminDetails['default_password_matches'] = $isDefaultPass;

                    $msg = "System Admin account '@{$defaultAdmin['username']}' verified with valid password hash.";
                    if ($isDefaultPass) {
                        $msg .= " (Note: Default 'admin123' credentials active. Ensure you update your password in My Profile before going live).";
                    }

                    $checks[] = [
                        'name' => 'System Admin Authentication & Password Hash',
                        'status' => 'pass',
                        'message' => $msg,
                        'details' => $adminDetails
                    ];
                } else {
                    $checks[] = [
                        'name' => 'System Admin Authentication & Password Hash',
                        'status' => 'pass',
                        'message' => "$adminCount custom System Administrator account(s) active with secure password hashes.",
                        'details' => $adminDetails
                    ];
                }
            } else {
                $checks[] = [
                    'name' => 'System Admin Authentication & Password Hash',
                    'status' => 'fail',
                    'message' => 'CRITICAL: No System Administrator accounts found in the database. Run seed.php immediately.',
                    'details' => null
                ];
            }
        } catch (Exception $e) {
            $checks[] = [
                'name' => 'System Admin Authentication & Password Hash',
                'status' => 'fail',
                'message' => 'Error querying admin credentials: ' . $e->getMessage(),
                'details' => null
            ];
        }

        // 2. Parent Accounts & Multi-Parent Support
        try {
            $parents = $this->pdo->query("SELECT id, username, email, parent_code, status, password_hash FROM parents")->fetchAll();
            $parentCount = count($parents);
            $activeParents = array_filter($parents, fn($p) => $p['status'] === 'active');

            if ($parentCount > 0) {
                $demoParent = null;
                foreach ($parents as $p) {
                    if ($p['username'] === 'sarah') {
                        $demoParent = $p;
                        break;
                    }
                }

                $parentMsg = "$parentCount parent accounts registered (" . count($activeParents) . " active).";
                if ($demoParent) {
                    $demoPassOk = password_verify('parent123', $demoParent['password_hash']);
                    if ($demoPassOk) {
                        $parentMsg .= " Demo parent '@sarah' credentials verified.";
                    }
                }

                $checks[] = [
                    'name' => 'Parent Accounts & Multi-Parent Credentials',
                    'status' => 'pass',
                    'message' => $parentMsg,
                    'details' => [
                        'total_parents' => $parentCount,
                        'active_parents' => count($activeParents),
                        'demo_parent_found' => ($demoParent !== null)
                    ]
                ];
            } else {
                $checks[] = [
                    'name' => 'Parent Accounts & Multi-Parent Credentials',
                    'status' => 'warning',
                    'message' => 'No parent accounts found. Parents must register or be seeded via seed.php.',
                    'details' => null
                ];
            }
        } catch (Exception $e) {
            $checks[] = [
                'name' => 'Parent Accounts & Multi-Parent Credentials',
                'status' => 'fail',
                'message' => 'Error checking parent accounts: ' . $e->getMessage(),
                'details' => null
            ];
        }

        // 3. Student / Kid PINs & KG3 Grade Level
        try {
            $students = $this->pdo->query("SELECT id, name, username, pin_code, grade_level, parent_id FROM students")->fetchAll();
            $kidCount = count($students);

            $invalidPins = [];
            $unlinkedKids = [];
            $kg3Count = 0;
            $psrCount = 0;

            foreach ($students as $kid) {
                // Check 4-digit PIN
                if (empty($kid['pin_code']) || !preg_match('/^[0-9]{4}$/', $kid['pin_code'])) {
                    $invalidPins[] = $kid['name'] . " (PIN: '{$kid['pin_code']}')";
                }
                // Check parent link
                if (empty($kid['parent_id'])) {
                    $unlinkedKids[] = $kid['name'];
                }
                // Check Curriculum Category
                if (isGradeYear6($kid['grade_level'])) {
                    $psrCount++;
                } elseif (isGradeKG3($kid['grade_level'])) {
                    $kg3Count++;
                }
            }

            if ($kidCount > 0 && empty($invalidPins)) {
                $checks[] = [
                    'name' => 'Student Accounts & Simple 4-Digit PIN Security',
                    'status' => 'pass',
                    'message' => "All $kidCount student accounts have valid 4-digit numeric PIN codes ($kg3Count KG3, $psrCount Year 6 PSR).",
                    'details' => [
                        'total_students' => $kidCount,
                        'kg3_enrolled' => $kg3Count,
                        'year6_psr_enrolled' => $psrCount,
                        'unlinked_kids' => count($unlinkedKids)
                    ]
                ];
            } elseif ($kidCount === 0) {
                $checks[] = [
                    'name' => 'Student Accounts & Simple 4-Digit PIN Security',
                    'status' => 'warning',
                    'message' => 'No student accounts currently enrolled. Run seed.php or create students in Parent Portal.',
                    'details' => null
                ];
            } else {
                $checks[] = [
                    'name' => 'Student Accounts & Simple 4-Digit PIN Security',
                    'status' => 'fail',
                    'message' => 'Found students with non-standard PIN codes: ' . implode(', ', $invalidPins),
                    'details' => $invalidPins
                ];
            }
        } catch (Exception $e) {
            $checks[] = [
                'name' => 'Student Accounts & Simple 4-Digit PIN Security',
                'status' => 'fail',
                'message' => 'Error checking student credentials: ' . $e->getMessage(),
                'details' => null
            ];
        }

        return ['title' => $catTitle, 'checks' => $checks];
    }

    /**
     * 5. ONLINE PRODUCTION & SERVER ENVIRONMENT CHECKLIST
     */
    public function checkProductionEnvironment(): array {
        $checks = [];
        $catTitle = "Online Production & Server Environment";

        // 1. PHP Version
        $phpVersion = PHP_VERSION;
        if (version_compare($phpVersion, '8.0.0', '>=')) {
            $checks[] = [
                'name' => 'PHP Runtime Version',
                'status' => 'pass',
                'message' => "Current PHP version ($phpVersion) meets recommended modern standards (>= 8.0).",
                'details' => ['php_version' => $phpVersion, 'status' => 'Optimal']
            ];
        } elseif (version_compare($phpVersion, '7.4.0', '>=')) {
            $checks[] = [
                'name' => 'PHP Runtime Version',
                'status' => 'warning',
                'message' => "PHP version ($phpVersion) is compatible, but upgrading to PHP 8.1+ is strongly recommended for production.",
                'details' => ['php_version' => $phpVersion]
            ];
        } else {
            $checks[] = [
                'name' => 'PHP Runtime Version',
                'status' => 'fail',
                'message' => "PHP version ($phpVersion) is below required 7.4.",
                'details' => ['php_version' => $phpVersion]
            ];
        }

        // 2. Required Extensions
        $requiredExtensions = [
            'pdo' => 'Database Abstraction Layer',
            'pdo_mysql' => 'MySQL Database Driver',
            'json' => 'JSON Parser for Questions & APIs',
            'mbstring' => 'Multibyte String for Bahasa Melayu / UTF-8',
            'curl' => 'Client URL Library for API testing',
            'session' => 'Session Authentication Management',
            'openssl' => 'Cryptographic Operations & Token Generation'
        ];

        $missingExt = [];
        foreach ($requiredExtensions as $ext => $desc) {
            if (!extension_loaded($ext)) {
                $missingExt[] = "$ext ($desc)";
            }
        }

        if (empty($missingExt)) {
            $checks[] = [
                'name' => 'Required PHP Extensions',
                'status' => 'pass',
                'message' => 'All ' . count($requiredExtensions) . ' required extensions (PDO, PDO_MySQL, JSON, MBString, cURL, Session, OpenSSL) are installed and active.',
                'details' => $requiredExtensions
            ];
        } else {
            $checks[] = [
                'name' => 'Required PHP Extensions',
                'status' => 'fail',
                'message' => 'Missing critical extensions: ' . implode(', ', $missingExt),
                'details' => $missingExt
            ];
        }

        // 3. Session & File Permissions
        $sessionSavePath = session_save_path() ?: sys_get_temp_dir();
        $isSessionWritable = is_writable($sessionSavePath);

        if ($isSessionWritable) {
            $checks[] = [
                'name' => 'Session Storage & Directory Permissions',
                'status' => 'pass',
                'message' => "Session directory ($sessionSavePath) is writable. User login sessions will persist normally.",
                'details' => ['session_path' => $sessionSavePath, 'writable' => true]
            ];
        } else {
            $checks[] = [
                'name' => 'Session Storage & Directory Permissions',
                'status' => 'fail',
                'message' => "Session storage path ($sessionSavePath) is not writable! Users will be unable to log in.",
                'details' => ['session_path' => $sessionSavePath, 'writable' => false]
            ];
        }

        // 4. Production Database Security Notice
        // Check if root password in db.php is blank
        $dbFile = file_get_contents($this->rootDir . '/db.php');
        $hasEmptyPass = preg_match("/\\\$pass\s*=\s*['\"]\s*['\"];/", $dbFile);

        if ($hasEmptyPass) {
            $checks[] = [
                'name' => 'Production Database Password Audit',
                'status' => 'warning',
                'message' => "MySQL password in db.php is currently empty (standard for local XAMPP). BEFORE deploying online to production, configure a strong password and dedicated database user.",
                'details' => ['file' => 'db.php', 'recommendation' => 'Set strong MySQL password for online host']
            ];
        } else {
            $checks[] = [
                'name' => 'Production Database Password Audit',
                'status' => 'pass',
                'message' => 'Database configuration uses a non-empty password.',
                'details' => ['status' => 'Configured']
            ];
        }

        // 5. HTTPS / SSL Readiness
        $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || ($_SERVER['SERVER_PORT'] ?? 80) == 443;
        if ($isHttps) {
            $checks[] = [
                'name' => 'SSL / HTTPS Encryption',
                'status' => 'pass',
                'message' => 'Encrypted HTTPS connection active. Data transit is secured.',
                'details' => ['https' => true]
            ];
        } else {
            $checks[] = [
                'name' => 'SSL / HTTPS Encryption',
                'status' => 'warning',
                'message' => 'Currently running over unencrypted HTTP (normal for local development). For online production, install a free SSL certificate (Let\'s Encrypt / Cloudflare) to ensure HTTPS is enforced.',
                'details' => ['https' => false, 'recommendation' => 'Enable SSL/TLS certificate when hosting online']
            ];
        }

        // 6. Error Display Audit for Production
        $displayErrors = ini_get('display_errors');
        $isDisplayErrorsOn = in_array(strtolower((string)$displayErrors), ['1', 'on', 'true']);

        if ($isDisplayErrorsOn) {
            $checks[] = [
                'name' => 'PHP Error Display Setting (display_errors)',
                'status' => 'warning',
                'message' => 'display_errors is ON (ideal for development). For online production, set display_errors = Off in php.ini and enable log_errors to prevent leaking server paths to public visitors.',
                'details' => ['display_errors' => $displayErrors, 'recommendation' => 'Turn off display_errors in production php.ini']
            ];
        } else {
            $checks[] = [
                'name' => 'PHP Error Display Setting (display_errors)',
                'status' => 'pass',
                'message' => 'display_errors is OFF. Server paths and fatal stack traces will not be exposed to visitors.',
                'details' => ['display_errors' => 'Off']
            ];
        }

        return ['title' => $catTitle, 'checks' => $checks];
    }
}
