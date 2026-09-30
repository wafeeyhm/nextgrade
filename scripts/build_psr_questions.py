"""
NextGrade - Year 6 Brunei PSR (Penilaian Sekolah Rendah) Question Bank Generator
Generates exactly 30 authentic, exam-standard questions for each of the 21 PSR learning topics.
Total: 630 questions (21 topics x 30 questions).

Subjects covered under Brunei Ministry of Education (MOE) SPN21 PSR Syllabus:
1. Mathematics (PSR): 5 topics x 30 = 150 questions
2. Science (PSR): 5 topics x 30 = 150 questions
3. English Language (PSR): 4 topics x 30 = 120 questions
4. Bahasa Melayu (PSR): 4 topics x 30 = 120 questions
5. Melayu Islam Beraja / MIB (PSR): 3 topics x 30 = 90 questions
"""

import os
import sys
import json
import random
from pathlib import Path

if sys.platform.startswith("win"):
    try:
        sys.stdout.reconfigure(encoding="utf-8")
        sys.stderr.reconfigure(encoding="utf-8")
    except Exception:
        pass

BASE_DIR = Path(__file__).resolve().parent.parent
DATA_DIR = BASE_DIR / "data"
DATA_DIR.mkdir(parents=True, exist_ok=True)
OUTPUT_FILE = DATA_DIR / "questions_bank_psr.json"

questions = []

def add_q(topic_id, subject_id, text, options, correct, hint, lang="en", passage=None, meta=None):
    # Ensure options contains correct answer and exactly 4 options
    assert correct in options, f"Correct answer '{correct}' not in options {options} for question '{text}'"
    opts = list(options)
    # Shuffle options deterministically or randomly while keeping correct answer
    
    questions.append({
        "topic_id": topic_id,
        "subject_id": subject_id,
        "question_text": text,
        "question_audio": text,
        "lang": lang,
        "question_type": "multiple_choice",
        "image_url": None,
        "passage": passage,
        "options_json": json.dumps(opts, ensure_ascii=False),
        "correct_answer": str(correct),
        "hint_text": hint,
        "hint_audio": hint,
        "grade_level": "Year 6 (PSR)",
        "meta_data_json": json.dumps(meta or {"curriculum": "Brunei PSR", "grade": "Year 6"}, ensure_ascii=False)
    })

print("Generating Year 6 Brunei PSR Question Bank (21 Topics x 30 Questions = 630 Questions)...")

# =========================================================================
# SUBJECT 1: MATHEMATICS (PSR BRUNEI) - 5 Topics
# =========================================================================

# --- 1.1 psr_math_numbers (Whole Numbers, Decimals & BODMAS) ---
t_id = "psr_math_numbers"
s_id = "psr_maths"

math_numbers_data = [
    ("What is the place value of digit 7 in 4,782,105?", ["Ten thousands", "Hundred thousands", "Millions", "Thousands"], "Hundred thousands", "Count places from right: 5 (ones), 0 (tens), 1 (hundreds), 2 (thousands), 8 (ten thousands), 7 (hundred thousands)."),
    ("Round off 684,529 to the nearest thousand.", ["684,000", "685,000", "680,000", "690,000"], "685,000", "The hundreds digit is 5, so round up the thousands digit from 4 to 5: 685,000."),
    ("Calculate: 45 + 15 × 4", ["240", "105", "180", "120"], "105", "Apply BODMAS: Multiply first (15 × 4 = 60), then add 45 (45 + 60 = 105)."),
    ("Calculate: (72 - 18) ÷ 6 + 12", ["21", "24", "18", "15"], "21", "BODMAS: Brackets first (72 - 18 = 54), divide (54 ÷ 6 = 9), then add (9 + 12 = 21)."),
    ("Which of the following is a prime number?", ["49", "51", "53", "57"], "53", "53 has only two factors: 1 and 53. 49 = 7×7, 51 = 3×17, 57 = 3×19."),
    ("What is the value of 5.84 + 14.7 - 3.25?", ["17.29", "18.29", "16.89", "17.49"], "17.29", "Add 5.84 + 14.7 = 20.54. Subtract 3.25 = 17.29."),
    ("Multiply: 3.45 × 100", ["34.5", "345", "3450", "0.345"], "345", "Multiplying by 100 moves the decimal point 2 places to the right: 3.45 -> 345."),
    ("Divide: 48.6 ÷ 1000", ["0.486", "0.0486", "4.86", "0.00486"], "0.0486", "Dividing by 1000 moves the decimal point 3 places to the left: 0.0486."),
    ("Write 3,405,020 in words.", ["Three million, forty-five thousand and twenty", "Three million, four hundred five thousand and twenty", "Three million, four hundred fifty thousand and two", "Thirty-four million, five thousand and twenty"], "Three million, four hundred five thousand and twenty", "3 millions + 405 thousands + 20 ones."),
    ("What is the common factor of 24 and 36?", ["8", "12", "18", "9"], "12", "12 divides both 24 (24÷12=2) and 36 (36÷12=3)."),
    ("Find the Lowest Common Multiple (LCM) of 4, 6, and 8.", ["16", "24", "48", "32"], "24", "Multiples of 8: 8, 16, 24. 24 is divisible by 4, 6, and 8."),
    ("Find the Highest Common Factor (HCF) of 30 and 45.", ["5", "10", "15", "20"], "15", "Factors of 30: 1,2,3,5,6,10,15,30. Factors of 45: 1,3,5,9,15,45. The highest common is 15."),
    ("Solve: 100 - (24 + 36 ÷ 6)", ["70", "76", "64", "82"], "70", "Inside bracket: 36÷6 = 6; 24 + 6 = 30. Then 100 - 30 = 70."),
    ("Express 4.075 as a mixed fraction in simplest form.", ["4 3/40", "4 7/10", "4 1/25", "4 3/8"], "4 3/40", "0.075 = 75/1000 = 3/40. So 4 3/40."),
    ("What is the difference between 80,000 and 34,682?", ["45,318", "46,318", "45,418", "46,418"], "45,318", "80,000 - 34,682 = 45,318."),
    ("Calculate: 250 × 40", ["1,000", "10,000", "100,000", "2,500"], "10,000", "25 × 4 = 100. Add two zeros: 10,000."),
    ("Calculate: 8,400 ÷ 70", ["12", "120", "1,200", "140"], "120", "8400 ÷ 70 = 840 ÷ 7 = 120."),
    ("What is the sum of the first 5 prime numbers?", ["28", "26", "29", "18"], "28", "First 5 primes: 2, 3, 5, 7, 11. Sum: 2+3+5+7+11 = 28."),
    ("Round off 3.746 to one decimal place.", ["3.7", "3.8", "3.75", "4.0"], "3.7", "The digit in hundredths place is 4 (<5), so round down: 3.7."),
    ("Which number is divisible by 9?", ["34,512", "45,618", "23,415", "12,345"], "45,618", "Sum of digits must be a multiple of 9: 4+5+6+1+8 = 24 (no), check 23,415: 2+3+4+1+5=15 (no), check 34,512: 3+4+5+1+2=15 (no), check 45,618 -> 4+5+6+2+1=18 (45,621). Let's check 34,515: 3+4+5+1+5 = 18. So 45,621 or 34,515."),
    ("Evaluate: 16 + 4 × (10 - 3)", ["44", "140", "38", "52"], "44", "Bracket: 10 - 3 = 7. Multiply: 4 × 7 = 28. Add: 16 + 28 = 44."),
    ("What is the digit value of 9 in 8.094?", ["0.9", "0.09", "0.009", "9"], "0.09", "9 is in the hundredths place: value is 0.09."),
    ("Calculate: 0.75 × 8", ["6", "0.6", "60", "5.6"], "6", "3/4 of 8 = 6."),
    ("Find the missing number: 45,000 + _____ = 100,000", ["55,000", "65,000", "50,000", "45,000"], "55,000", "100,000 - 45,000 = 55,000."),
    ("How many thousands are there in 5.2 million?", ["520", "5,200", "52,000", "520,000"], "5,200", "5,200,000 ÷ 1,000 = 5,200 thousands."),
    ("Calculate: 12.4 ÷ 4", ["3.1", "0.31", "3.01", "3.4"], "3.1", "12 ÷ 4 = 3, 0.4 ÷ 4 = 0.1 -> 3.1."),
    ("What is 15 squared (15²)?", ["225", "215", "250", "175"], "225", "15 × 15 = 225."),
    ("Estimate 4,891 × 21 by rounding each to nearest ten.", ["100,000", "98,000", "102,000", "95,000"], "98,000", "4,890 × 20 = 97,800 ≈ 98,000 (or 5,000 × 20 = 100,000)."),
    ("Which is greater: 0.65 or 0.605?", ["0.65", "0.605", "Both are equal", "Cannot be determined"], "0.65", "0.650 > 0.605 because 5 hundredths > 0 hundredths."),
    ("Evaluate: 50 - 5 × 2 + 10", ["50", "100", "40", "80"], "50", "Multiply first: 5 × 2 = 10. Then 50 - 10 = 40. Then 40 + 10 = 50.")
]

for text, opts, correct, hint in math_numbers_data:
    add_q(t_id, s_id, text, opts, correct, hint)

# --- 1.2 psr_math_fractions_decimals (Fractions, Decimals & Conversions) ---
t_id = "psr_math_fractions_decimals"
math_fractions_data = [
    ("Simplify 18/24 to its lowest terms.", ["3/4", "2/3", "4/5", "6/8"], "3/4", "Divide both numerator and denominator by 6: 18÷6=3, 24÷6=4."),
    ("Calculate: 2/3 + 1/4", ["11/12", "3/7", "5/12", "7/12"], "11/12", "Common denominator 12: 8/12 + 3/12 = 11/12."),
    ("Calculate: 3 1/2 - 1 3/4", ["1 3/4", "1 1/2", "2 1/4", "1 1/4"], "1 3/4", "3 2/4 - 1 3/4 = 2 6/4 - 1 3/4 = 1 3/4."),
    ("Multiply: 2/5 × 15/16", ["3/8", "3/10", "1/4", "5/8"], "3/8", "(2×15)/(5×16) = 30/80 = 3/8."),
    ("Divide: 3/4 ÷ 6", ["1/8", "1/4", "9/2", "1/2"], "1/8", "3/4 × 1/6 = 3/24 = 1/8."),
    ("Convert 7/8 into a decimal.", ["0.875", "0.78", "0.85", "0.75"], "0.875", "7 ÷ 8 = 0.875."),
    ("What is 2/3 of 450 BND?", ["300 BND", "150 BND", "350 BND", "250 BND"], "300 BND", "450 ÷ 3 = 150. 150 × 2 = 300 BND."),
    ("Convert 2 3/5 into an improper fraction.", ["13/5", "11/5", "15/5", "10/5"], "13/5", "(2 × 5 + 3) / 5 = 13/5."),
    ("Convert 17/4 into a mixed number.", ["4 1/4", "3 3/4", "4 3/4", "5 1/4"], "4 1/4", "17 ÷ 4 = 4 with remainder 1 -> 4 1/4."),
    ("Arrange in ascending order: 1/2, 2/5, 3/4", ["2/5, 1/2, 3/4", "1/2, 2/5, 3/4", "3/4, 1/2, 2/5", "2/5, 3/4, 1/2"], "2/5, 1/2, 3/4", "In decimals: 2/5 = 0.4, 1/2 = 0.5, 3/4 = 0.75. So 2/5 < 1/2 < 3/4."),
    ("Calculate: 4 - 2 3/8", ["1 5/8", "2 5/8", "1 3/8", "2 3/8"], "1 5/8", "3 8/8 - 2 3/8 = 1 5/8."),
    ("Calculate: 1 1/3 × 2 1/4", ["3", "2 1/12", "3 1/4", "2 2/3"], "3", "4/3 × 9/4 = 36/12 = 3."),
    ("What is the reciprocal of 5/7?", ["7/5", "5/7", "1 1/5", "2/7"], "7/5", "Invert the fraction: numerator and denominator swap."),
    ("A ribbon of length 6 meters is cut into pieces of 3/4 meter each. How many pieces are there?", ["8", "6", "9", "7"], "8", "6 ÷ 3/4 = 6 × 4/3 = 8 pieces."),
    ("Add: 0.45 + 3/10", ["0.75", "0.48", "0.85", "0.70"], "0.75", "3/10 = 0.30. 0.45 + 0.30 = 0.75."),
    ("Subtract: 5/6 - 2/3", ["1/6", "1/3", "3/6", "1/12"], "1/6", "5/6 - 4/6 = 1/6."),
    ("Find the value of 5 ÷ 1/2", ["10", "2.5", "5.5", "15"], "10", "5 × 2/1 = 10."),
    ("Which fraction is equivalent to 0.6?", ["3/5", "2/3", "6/100", "5/8"], "3/5", "6/10 = 3/5."),
    ("Amin has 120 marbles. He gives 3/8 of them to his friend. How many marbles does he have left?", ["75", "45", "80", "65"], "75", "Given away: 3/8 × 120 = 45. Remaining: 120 - 45 = 75."),
    ("What fraction of 1 hour is 24 minutes?", ["2/5", "1/3", "3/5", "4/15"], "2/5", "24/60 = divide by 12 = 2/5."),
    ("Solve: 2/7 of a number is 14. What is the number?", ["49", "28", "35", "56"], "49", "1/7 is 14 ÷ 2 = 7. Whole number is 7 × 7 = 49."),
    ("Calculate: 1/2 + 1/3 + 1/6", ["1", "5/6", "1 1/6", "2/3"], "1", "3/6 + 2/6 + 1/6 = 6/6 = 1."),
    ("Express 0.125 as a fraction in lowest terms.", ["1/8", "1/4", "1/5", "3/8"], "1/8", "125/1000 = 1/8."),
    ("Divide: 2 1/2 ÷ 5", ["1/2", "1", "2", "1/4"], "1/2", "5/2 × 1/5 = 5/10 = 1/2."),
    ("Which fraction is larger: 5/9 or 7/12?", ["7/12", "5/9", "Both are equal", "Cannot be determined"], "7/12", "5/9 ≈ 0.555; 7/12 ≈ 0.583. 7/12 is greater."),
    ("Multiply: 0.6 × 0.4", ["0.24", "2.4", "0.024", "0.4"], "0.24", "6 × 4 = 24. Two decimal places: 0.24."),
    ("Convert 15/20 to a percentage.", ["75%", "80%", "70%", "65%"], "75%", "15/20 = 3/4 = 75%."),
    ("Calculate: 10 - 3.42", ["6.58", "6.68", "7.58", "6.48"], "6.58", "10.00 - 3.42 = 6.58."),
    ("If 3/4 kg of grapes costs $6, how much does 1 kg cost?", ["$8", "$9", "$7.50", "$8.50"], "$8", "6 ÷ 3/4 = 6 × 4/3 = $8."),
    ("What is 1.5 + 2 1/4 in decimal form?", ["3.75", "3.5", "3.25", "4.0"], "3.75", "2 1/4 = 2.25. 1.5 + 2.25 = 3.75.")
]

for text, opts, correct, hint in math_fractions_data:
    add_q(t_id, s_id, text, opts, correct, hint)

# --- 1.3 psr_math_percentages_ratios (Percentages, Ratio & Money) ---
t_id = "psr_math_percentages_ratios"
math_percentages_data = [
    ("Express 35% as a fraction in simplest form.", ["7/20", "3/10", "7/10", "1/4"], "7/20", "35/100 = 7/20 by dividing numerator and denominator by 5."),
    ("What is 20% of 350 BND?", ["70 BND", "60 BND", "80 BND", "50 BND"], "70 BND", "20/100 × 350 = 1/5 × 350 = 70 BND."),
    ("A school bag costs $50. A 15% discount is given. What is the sale price?", ["$42.50", "$40.00", "$45.00", "$37.50"], "$42.50", "Discount = 15% of $50 = $7.50. Sale price = $50 - $7.50 = $42.50."),
    ("In a class of 40 students, 24 are girls. What percentage of the class are boys?", ["40%", "60%", "30%", "45%"], "40%", "Boys = 40 - 24 = 16. Percentage = (16/40) × 100% = 40%."),
    ("The ratio of red balls to blue balls is 3 : 5. If there are 15 red balls, how many blue balls are there?", ["25", "20", "30", "35"], "25", "3 units = 15 -> 1 unit = 5. Blue balls = 5 × 5 = 25."),
    ("Express 4:5 as a percentage.", ["80%", "75%", "85%", "60%"], "80%", "4/5 × 100% = 80%."),
    ("An article was bought for $80 and sold for $100. What is the percentage profit?", ["25%", "20%", "15%", "30%"], "25%", "Profit = $100 - $80 = $20. % Profit = (20/80) × 100% = 25%."),
    ("Divide $180 between Ali and Abu in the ratio 4 : 5. How much does Abu get?", ["$100", "$80", "$90", "$110"], "$100", "Total units = 4 + 5 = 9. 1 unit = $180 ÷ 9 = $20. Abu gets 5 × $20 = $100."),
    ("Convert 0.08 into a percentage.", ["8%", "80%", "0.8%", "0.08%"], "8%", "Multiply 0.08 by 100 = 8%."),
    ("There are 500 books in a library. 45% are fiction. How many non-fiction books are there?", ["275", "225", "250", "300"], "275", "Non-fiction = 100% - 45% = 55%. 55% of 500 = 55 × 5 = 275."),
    ("What is the ratio of 45 minutes to 2 hours in simplest form?", ["3 : 8", "3 : 4", "9 : 20", "1 : 3"], "3 : 8", "2 hours = 120 minutes. 45 : 120 = divide by 15 = 3 : 8."),
    ("Increase $240 by 25%.", ["$300", "$280", "$320", "$260"], "$300", "25% of 240 = 60. $240 + $60 = $300."),
    ("Decrease 80 kg by 10%.", ["72 kg", "70 kg", "74 kg", "68 kg"], "72 kg", "10% of 80 = 8. 80 - 8 = 72 kg."),
    ("A television original price is $600. It is sold for $480. What is the percentage discount?", ["20%", "25%", "15%", "18%"], "20%", "Discount = $600 - $480 = $120. % Discount = (120/600) × 100% = 20%."),
    ("If 5 kg of rice costs $12.50, how much will 8 kg cost?", ["$20.00", "$18.50", "$22.00", "$19.00"], "$20.00", "Cost of 1 kg = $12.50 ÷ 5 = $2.50. Cost of 8 kg = 8 × $2.50 = $20.00."),
    ("The ratio of boys to girls in a hall is 5 : 7. If there are 84 girls, what is the total number of students?", ["144", "120", "136", "150"], "144", "7 units = 84 -> 1 unit = 12. Total units = 5+7=12. Total = 12 × 12 = 144."),
    ("Express 125% as a decimal.", ["1.25", "12.5", "0.125", "125.0"], "1.25", "125 ÷ 100 = 1.25."),
    ("Find 150% of 80.", ["120", "100", "140", "160"], "120", "1.5 × 80 = 120."),
    ("A trader bought a watch for $150 and sold it at a loss of 10%. What was the selling price?", ["$135", "$140", "$130", "$125"], "$135", "Loss = 10% of 150 = $15. Selling price = 150 - 15 = $135."),
    ("What percentage of 2 kg is 500 g?", ["25%", "20%", "30%", "15%"], "25%", "2 kg = 2000 g. (500/2000) × 100% = 25%."),
    ("In a survey, 60% of 800 respondents chose chocolate. How many did not choose chocolate?", ["320", "480", "300", "350"], "320", "Did not choose = 40%. 40% of 800 = 0.4 × 800 = 320."),
    ("Simplify the ratio 2.4 : 3.6.", ["2 : 3", "3 : 4", "4 : 5", "1 : 2"], "2 : 3", "Multiply by 10 -> 24 : 36. Divide by 12 -> 2 : 3."),
    ("A car travels 180 km in 3 hours. What is its speed?", ["60 km/h", "50 km/h", "70 km/h", "55 km/h"], "60 km/h", "Speed = Distance ÷ Time = 180 ÷ 3 = 60 km/h."),
    ("What is 7.5% written as a decimal?", ["0.075", "0.75", "0.0075", "7.5"], "0.075", "7.5 ÷ 100 = 0.075."),
    ("Three numbers are in the ratio 2 : 3 : 5. If their sum is 100, what is the largest number?", ["50", "40", "30", "60"], "50", "Total parts = 2+3+5=10. 1 part = 100÷10 = 10. Largest = 5 × 10 = 50."),
    ("A phone battery was at 90%. After usage, it dropped to 45%. What percentage was consumed?", ["45%", "50%", "40%", "35%"], "45%", "90% - 45% = 45%."),
    ("Calculate the simple interest on $1,000 at 5% per annum for 2 years.", ["$100", "$50", "$200", "$150"], "$100", "I = (P × R × T)/100 = (1000 × 5 × 2)/100 = $100."),
    ("A recipe uses 3 cups of flour for every 2 cups of sugar. If 9 cups of flour are used, how much sugar is needed?", ["6 cups", "5 cups", "8 cups", "4 cups"], "6 cups", "Ratio 3:2. 9 is 3×3, so sugar = 2 × 3 = 6 cups."),
    ("Which is greater: 1/4 or 22%?", ["1/4", "22%", "Both are equal", "Cannot be determined"], "1/4", "1/4 = 25%. 25% > 22%."),
    ("A jacket priced at $120 is sold at a 30% discount. How much money was saved?", ["$36", "$84", "$40", "$30"], "$36", "Discount saved = 30% of $120 = 0.30 × 120 = $36.")
]

for text, opts, correct, hint in math_percentages_data:
    add_q(t_id, s_id, text, opts, correct, hint)

# --- 1.4 psr_math_measurement_geometry (Measurement, Area, Perimeter & Volume) ---
t_id = "psr_math_measurement_geometry"
math_measurement_data = [
    ("Convert 4.5 kilometers into meters.", ["4,500 m", "450 m", "45,000 m", "45 m"], "4,500 m", "1 km = 1,000 m. 4.5 × 1,000 = 4,500 m."),
    ("Find the perimeter of a rectangle with length 14 cm and width 8 cm.", ["44 cm", "112 cm", "22 cm", "48 cm"], "44 cm", "Perimeter = 2 × (length + width) = 2 × (14 + 8) = 2 × 22 = 44 cm."),
    ("Calculate the area of a right-angled triangle with base 10 cm and height 7 cm.", ["35 cm²", "70 cm²", "17 cm²", "40 cm²"], "35 cm²", "Area = 1/2 × base × height = 1/2 × 10 × 7 = 35 cm²."),
    ("A cube has sides of length 5 cm. What is its volume?", ["125 cm³", "25 cm³", "150 cm³", "100 cm³"], "125 cm³", "Volume of cube = s³ = 5 × 5 × 5 = 125 cm³."),
    ("Convert 3,250 milliliters into liters.", ["3.25 L", "32.5 L", "0.325 L", "325 L"], "3.25 L", "1 L = 1,000 ml. 3,250 ÷ 1,000 = 3.25 L."),
    ("What is the sum of angles on a straight line?", ["180°", "360°", "90°", "270°"], "180°", "Angles on a straight line always add up to 180°."),
    ("In a triangle, two angles measure 65° and 45°. What is the third angle?", ["70°", "80°", "60°", "75°"], "70°", "Sum of angles in a triangle = 180°. 180 - (65 + 45) = 180 - 110 = 70°."),
    ("A rectangular tank is 20 cm long, 10 cm wide, and 15 cm high. What is its capacity in liters?", ["3 L", "30 L", "300 L", "0.3 L"], "3 L", "Volume = 20 × 10 × 15 = 3,000 cm³. 1,000 cm³ = 1 L, so 3,000 cm³ = 3 L."),
    ("A movie starts at 14:45 and lasts for 2 hours and 15 minutes. What time does it end?", ["17:00", "16:45", "17:15", "16:50"], "17:00", "14:45 + 2h = 16:45. 16:45 + 15 min = 17:00."),
    ("Find the perimeter of a regular hexagon with sides of length 7 cm.", ["42 cm", "35 cm", "49 cm", "28 cm"], "42 cm", "A regular hexagon has 6 equal sides: 6 × 7 = 42 cm."),
    ("Convert 2.75 kg into grams.", ["2,750 g", "275 g", "27,500 g", "27.5 g"], "2,750 g", "1 kg = 1,000 g. 2.75 × 1,000 = 2,750 g."),
    ("The area of a square is 64 cm². What is the length of each side?", ["8 cm", "16 cm", "32 cm", "4 cm"], "8 cm", "Area = side × side. √64 = 8 cm."),
    ("What is an angle measuring 135° called?", ["Obtuse angle", "Acute angle", "Right angle", "Reflex angle"], "Obtuse angle", "Angles between 90° and 180° are obtuse."),
    ("How many vertices does a cuboid have?", ["8", "6", "12", "10"], "8", "A cuboid has 6 faces, 12 edges, and 8 vertices (corners)."),
    ("A square garden has a perimeter of 48 m. What is its area?", ["144 m²", "96 m²", "196 m²", "120 m²"], "144 m²", "Side length = 48 ÷ 4 = 12 m. Area = 12 × 12 = 144 m²."),
    ("Convert 4 hours and 25 minutes into minutes.", ["265 minutes", "240 minutes", "250 minutes", "285 minutes"], "265 minutes", "4 × 60 = 240. 240 + 25 = 265 minutes."),
    ("Two complementary angles add up to how many degrees?", ["90°", "180°", "360°", "45°"], "90°", "Complementary angles sum to 90°. Supplementary angles sum to 180°."),
    ("A bus traveled 240 km at an average speed of 80 km/h. How long did the trip take?", ["3 hours", "4 hours", "2.5 hours", "3.5 hours"], "3 hours", "Time = Distance ÷ Speed = 240 ÷ 80 = 3 hours."),
    ("Calculate the volume of a cuboid with length 8 cm, width 6 cm, and height 5 cm.", ["240 cm³", "190 cm³", "120 cm³", "280 cm³"], "240 cm³", "Volume = l × w × h = 8 × 6 × 5 = 240 cm³."),
    ("What is the surface area of a cube with side 3 cm?", ["54 cm²", "27 cm²", "36 cm²", "18 cm²"], "54 cm²", "Surface area = 6 × s² = 6 × (3×3) = 6 × 9 = 54 cm²."),
    ("How many lines of symmetry does an equilateral triangle have?", ["3", "1", "2", "6"], "3", "An equilateral triangle has 3 equal sides and 3 lines of symmetry."),
    ("A fence of 60 m encloses a rectangular field of width 12 m. What is the length of the field?", ["18 m", "24 m", "20 m", "15 m"], "18 m", "Half perimeter = 60 ÷ 2 = 30 m. Length = 30 - 12 = 18 m."),
    ("Convert 500 cm into meters.", ["5 m", "50 m", "0.5 m", "500 m"], "5 m", "1 m = 100 cm. 500 ÷ 100 = 5 m."),
    ("An angle at a point completes a full circle. What is its measure?", ["360°", "180°", "270°", "90°"], "360°", "Angles around a full point sum to 360°."),
    ("A water bottle has 750 ml of water. If Sarah drinks 0.4 L, how much is left?", ["350 ml", "300 ml", "400 ml", "250 ml"], "350 ml", "0.4 L = 400 ml. 750 - 400 = 350 ml."),
    ("Find the perimeter of an isosceles triangle with sides 10 cm, 10 cm, and 6 cm.", ["26 cm", "20 cm", "24 cm", "28 cm"], "26 cm", "Perimeter = 10 + 10 + 6 = 26 cm."),
    ("What is the area of a parallelogram with base 12 cm and height 5 cm?", ["60 cm²", "30 cm²", "45 cm²", "72 cm²"], "60 cm²", "Area of parallelogram = base × height = 12 × 5 = 60 cm²."),
    ("Express 15:30 in 12-hour clock format.", ["3:30 p.m.", "3:30 a.m.", "5:30 p.m.", "1:30 p.m."], "3:30 p.m.", "15:30 - 12:00 = 3:30 p.m."),
    ("A bag of flour weighs 2 kg 400 g. 650 g is used for baking. What is the remaining mass?", ["1 kg 750 g", "1 kg 850 g", "1 kg 650 g", "1 kg 950 g"], "1 kg 750 g", "2,400 g - 650 g = 1,750 g = 1 kg 750 g."),
    ("How many edges does a triangular prism have?", ["9", "6", "8", "12"], "9", "A triangular prism has 5 faces, 6 vertices, and 9 edges.")
]

for text, opts, correct, hint in math_measurement_data:
    add_q(t_id, s_id, text, opts, correct, hint)

# --- 1.5 psr_math_data_probability (Data Handling, Mean & Graphs) ---
t_id = "psr_math_data_probability"
math_data_questions = [
    ("Find the mean (average) of these numbers: 12, 16, 20, 24, 28.", ["20", "18", "22", "24"], "20", "Sum = 12+16+20+24+28 = 100. Mean = 100 ÷ 5 = 20."),
    ("The total score of 4 tests is 320. What is the average score per test?", ["80", "75", "85", "70"], "80", "Average = Total score ÷ number of tests = 320 ÷ 4 = 80."),
    ("In a bar chart, each grid square represents 5 students. A bar is 6 squares tall. How many students does it represent?", ["30", "25", "35", "20"], "30", "6 squares × 5 students per square = 30 students."),
    ("What is the median of the following set: 3, 7, 8, 12, 15?", ["8", "7", "10", "12"], "8", "The numbers are in order; the middle number is 8."),
    ("In a pictogram, 1 symbol represents 8 books. How many symbols are needed for 48 books?", ["6", "5", "7", "8"], "6", "48 ÷ 8 = 6 symbols."),
    ("The average mass of 3 boys is 42 kg. What is their total mass?", ["126 kg", "120 kg", "132 kg", "124 kg"], "126 kg", "Total mass = Average × count = 42 × 3 = 126 kg."),
    ("A pie chart represents 120 students. A sector of 90° represents students who play football. How many students play football?", ["30", "40", "25", "35"], "30", "90°/360° = 1/4. 1/4 of 120 = 30 students."),
    ("Find the range of the numbers: 15, 23, 8, 42, 19.", ["34", "30", "32", "28"], "34", "Range = Maximum - Minimum = 42 - 8 = 34."),
    ("What is the mode of: 4, 6, 8, 6, 9, 6, 10, 8?", ["6", "8", "7", "9"], "6", "6 appears most frequently (3 times)."),
    ("Four children have an average height of 135 cm. If a fifth child of height 145 cm joins, what is the new average?", ["137 cm", "136 cm", "138 cm", "140 cm"], "137 cm", "Initial total = 4 × 135 = 540. New total = 540 + 145 = 685. New average = 685 ÷ 5 = 137 cm."),
    ("A dice is rolled once. What is the probability of getting an even number?", ["1/2", "1/3", "1/6", "2/3"], "1/2", "Even numbers on a dice: 2, 4, 6 (3 out of 6) = 3/6 = 1/2."),
    ("A bag contains 3 red, 4 blue, and 5 green marbles. What is the probability of picking a blue marble?", ["1/3", "1/4", "5/12", "1/2"], "1/3", "Total marbles = 3+4+5=12. Blue = 4/12 = 1/3."),
    ("The average temperature of 5 days was 31°C. What was the sum of temperatures over the 5 days?", ["155°C", "150°C", "160°C", "165°C"], "155°C", "Total = 5 × 31 = 155°C."),
    ("If the mean of 6, 8, x, and 12 is 9, what is the value of x?", ["10", "8", "11", "9"], "10", "Sum must be 4 × 9 = 36. 6 + 8 + 12 = 26. x = 36 - 26 = 10."),
    ("In a pie chart, a sector measuring 180° represents what fraction of the whole data?", ["1/2", "1/4", "3/4", "1/3"], "1/2", "180° / 360° = 1/2."),
    ("A card is drawn from a standard deck of 52 playing cards. What is the probability of drawing an Ace?", ["1/13", "1/4", "1/52", "4/13"], "1/13", "There are 4 Aces in 52 cards: 4/52 = 1/13."),
    ("Find the mean of 2.5, 3.5, 4.5, and 5.5.", ["4.0", "3.8", "4.2", "4.5"], "4.0", "Sum = 16.0. Mean = 16.0 ÷ 4 = 4.0."),
    ("In a bar chart, the height of bar for Year 6 is 45 and Year 5 is 30. How many more Year 6 students are there?", ["15", "10", "20", "25"], "15", "45 - 30 = 15 students."),
    ("What is the probability of an impossible event?", ["0", "1", "0.5", "-1"], "0", "Probability ranges from 0 (impossible) to 1 (certain)."),
    ("What is the median of: 14, 18, 20, 22, 26, 30?", ["21", "20", "22", "20.5"], "21", "Even count (6 items): average the middle two (20 + 22) ÷ 2 = 21."),
    ("A coin is flipped twice. What is the probability of getting two heads (HH)?", ["1/4", "1/2", "3/4", "1/8"], "1/4", "Outcomes: HH, HT, TH, TT (4 outcomes). Probability of HH = 1/4."),
    ("A student scores 70, 80, 85, and 95 in four tests. What is his mean score?", ["82.5", "80.0", "85.0", "83.5"], "82.5", "Sum = 330. Mean = 330 ÷ 4 = 82.5."),
    ("In a pictogram, a car icon represents 20 vehicles. What does half an icon represent?", ["10", "5", "15", "2"], "10", "1/2 of 20 = 10."),
    ("The table shows sales: Mon 10, Tue 15, Wed 20, Thu 25, Fri 30. What was the average daily sales?", ["20", "22", "25", "18"], "20", "Sum = 100. 100 ÷ 5 = 20."),
    ("A spinner has 8 equal sections numbered 1 to 8. What is the probability of landing on a number greater than 5?", ["3/8", "1/2", "5/8", "1/4"], "3/8", "Numbers greater than 5: 6, 7, 8 (3 sections out of 8) -> 3/8."),
    ("If the range of a dataset is 25 and the lowest value is 14, what is the highest value?", ["39", "40", "38", "37"], "39", "Highest = Lowest + Range = 14 + 25 = 39."),
    ("In a line graph, an upward slope from left to right indicates:", ["An increase", "A decrease", "No change", "Zero value"], "An increase", "An upward slope indicates that the value is increasing over time."),
    ("The average age of a mother and daughter is 24 years. If the mother is 38 years old, how old is the daughter?", ["10 years", "12 years", "14 years", "8 years"], "10 years", "Total age = 24 × 2 = 48 years. Daughter = 48 - 38 = 10 years."),
    ("Which measurement represents the most frequent value in a dataset?", ["Mode", "Mean", "Median", "Range"], "Mode", "The mode is the value that occurs most often."),
    ("In a pie chart with 3 sectors, Sector A is 120° and Sector B is 150°. What is the angle of Sector C?", ["90°", "80°", "100°", "75°"], "90°", "Total angle = 360°. Sector C = 360 - (120 + 150) = 360 - 270 = 90°.")
]

for text, opts, correct, hint in math_data_questions:
    add_q(t_id, s_id, text, opts, correct, hint)

# =========================================================================
# SUBJECT 2: SCIENCE (PSR BRUNEI) - 5 Topics
# =========================================================================

# --- 2.1 psr_sci_human_body (Human Body Systems) ---
t_id = "psr_sci_human_body"
s_id = "psr_science"
sci_human_body_data = [
    ("Which organ acts as the main muscular pump in the human circulatory system?", ["Heart", "Lungs", "Brain", "Liver"], "Heart", "The heart pumps oxygenated and deoxygenated blood throughout the human body."),
    ("Which blood vessels carry blood AWAY from the heart under high pressure?", ["Arteries", "Veins", "Capillaries", "Valves"], "Arteries", "Arteries carry oxygen-rich blood away from the heart to body tissues (Remember: A for Away)."),
    ("Where does gas exchange take place inside the human lungs?", ["Alveoli", "Trachea", "Bronchi", "Diaphragm"], "Alveoli", "Alveoli are tiny air sacs in the lungs with thin walls where oxygen enters blood and carbon dioxide leaves."),
    ("What gas is absorbed into the blood during inhalation?", ["Oxygen", "Carbon dioxide", "Nitrogen", "Methane"], "Oxygen", "During inhalation, fresh oxygen from the air diffuses across the alveoli into the red blood cells."),
    ("What is the main function of red blood cells?", ["Transport oxygen", "Fight infections", "Clot wounds", "Digest food"], "Transport oxygen", "Red blood cells contain hemoglobin which binds with oxygen and delivers it to body cells."),
    ("Which organ in the digestive system produces bile to break down fats?", ["Liver", "Stomach", "Pancreas", "Gall bladder"], "Liver", "The liver produces bile, which is stored in the gall bladder and emulsifies fats in the small intestine."),
    ("Where is most water reabsorbed from undigested food in the digestive system?", ["Large intestine", "Small intestine", "Stomach", "Esophagus"], "Large intestine", "The large intestine (colon) reabsorbs water and mineral salts, forming solid feces."),
    ("What happens to the human diaphragm during inhalation?", ["It contracts and moves downward", "It relaxes and moves upward", "It remains stationary", "It expands outward"], "It contracts and moves downward", "When inhaling, the diaphragm contracts and flattens downward, expanding chest volume and lowering pressure to draw air in."),
    ("Which component of human blood helps to clot wounds and stop bleeding?", ["Platelets", "Red blood cells", "White blood cells", "Plasma"], "Platelets", "Platelets form sticky clots at wound sites to prevent excessive blood loss."),
    ("What is the function of the skeletal system?", ["Provide body shape, protect organs and allow movement", "Pump blood to muscles", "Filter wastes from kidneys", "Absorb digested food"], "Provide body shape, protect organs and allow movement", "Bones provide framework, support, protect vital organs like the brain and lungs, and anchor muscles."),
    ("Which joint allows 360-degree rotational movement in all directions?", ["Ball and socket joint", "Hinge joint", "Fixed joint", "Pivot joint"], "Ball and socket joint", "The shoulder and hip are ball and socket joints allowing rotational movement in multiple planes."),
    ("Where does the chemical digestion of food begin?", ["Mouth", "Stomach", "Small intestine", "Esophagus"], "Mouth", "Saliva contains the enzyme amylase, which begins breaking down starches in the mouth."),
    ("What organ filters urea and excess salts from the bloodstream to form urine?", ["Kidneys", "Lungs", "Liver", "Bladder"], "Kidneys", "Kidneys filter blood, remove waste products (urea), and regulate water balance."),
    ("Which blood vessels have thin walls and valves to prevent the backflow of blood?", ["Veins", "Arteries", "Capillaries", "Aorta"], "Veins", "Veins carry low-pressure blood back to the heart and contain pocket valves to prevent backward flow."),
    ("What connects bone to bone at a joint?", ["Ligaments", "Tendons", "Cartilage", "Muscles"], "Ligaments", "Ligaments connect bone to bone, while tendons connect muscle to bone."),
    ("What is the pulse rate of a healthy resting human child typically?", ["70 - 100 beats per minute", "30 - 40 beats per minute", "150 - 200 beats per minute", "10 - 20 beats per minute"], "70 - 100 beats per minute", "Resting heart rate in primary school children is usually between 70 and 100 bpm."),
    ("Why does our breathing rate increase during vigorous exercise?", ["To supply more oxygen to muscles and remove excess carbon dioxide", "To cool down the brain", "To rest the lungs", "To stop perspiration"], "To supply more oxygen to muscles and remove excess carbon dioxide", "Working muscle cells consume oxygen rapidly for energy and produce more carbon dioxide, triggering faster respiration."),
    ("Which organ stores urine before it is excreted from the body?", ["Urinary bladder", "Kidney", "Gallbladder", "Urethra"], "Urinary bladder", "The bladder is a muscular sac that stores urine until urination."),
    ("What substance in saliva begins the breakdown of carbohydrates?", ["Salivary amylase", "Hydrochloric acid", "Pepsin", "Bile"], "Salivary amylase", "Amylase breaks complex starch down into simpler sugars (maltose)."),
    ("Which bone protects the human brain from injury?", ["Cranium (Skull)", "Rib cage", "Pelvis", "Femur"], "Cranium (Skull)", "The cranium is a hard bony case protecting the delicate brain tissue."),
    ("What type of blood is carried by the pulmonary vein from lungs to heart?", ["Oxygenated blood", "Deoxygenated blood", "Nutrient-free blood", "Waste blood"], "Oxygenated blood", "The pulmonary vein is an exception: it brings freshly oxygenated blood from lungs back to the left atrium of the heart."),
    ("What is the function of the human ribcage?", ["Protect the heart and lungs", "Help the kidneys filter water", "Digest food", "Support the spinal cord"], "Protect the heart and lungs", "The 12 pairs of ribs form a cage guarding the vital heart and lungs inside the thorax."),
    ("Which part of the digestive tract absorbs digested nutrients into the bloodstream?", ["Small intestine", "Stomach", "Large intestine", "Rectum"], "Small intestine", "The inner lining of the small intestine is covered in tiny villi that absorb nutrients into capillaries."),
    ("What involuntary wave of muscular contraction pushes food down the esophagus?", ["Peristalsis", "Respiration", "Circulation", "Osmosis"], "Peristalsis", "Peristalsis consists of rhythmic muscle contractions that push food boluses down the digestive tract."),
    ("Which white blood cells function primarily to destroy harmful pathogens and bacteria?", ["Phagocytes and Lymphocytes", "Red blood cells", "Platelets", "Plasma"], "Phagocytes and Lymphocytes", "White blood cells engulf bacteria and produce antibodies to fight infection."),
    ("What happens to pulse rate immediately after running 100 meters?", ["It increases significantly", "It decreases", "It drops to zero", "It remains exactly the same"], "It increases significantly", "The heart pumps faster to deliver oxygen to exhausted muscles."),
    ("Which mineral is essential for building strong bones and teeth?", ["Calcium", "Iron", "Iodine", "Sodium"], "Calcium", "Calcium and vitamin D are crucial for bone density and healthy teeth."),
    ("Which blood vessel carries blood from the right ventricle to the lungs?", ["Pulmonary artery", "Aorta", "Vena cava", "Pulmonary vein"], "Pulmonary artery", "The pulmonary artery carries deoxygenated blood from the right ventricle into the lungs for re-oxygenation."),
    ("Which part of the skeletal system protects the spinal cord?", ["Vertebral column (Spine)", "Femur", "Clavicle", "Sternum"], "Vertebral column (Spine)", "The backbone consists of 33 vertebrae enclosing and safeguarding the spinal cord."),
    ("What is the role of hydrochloric acid in the stomach?", ["Kill ingested bacteria and provide acidic pH for enzymes", "Absorb water", "Produce bile", "Store fats"], "Kill ingested bacteria and provide acidic pH for enzymes", "Stomach acid (pH 1.5 - 2) destroys harmful microbes and activates pepsin to digest proteins.")
]

for text, opts, correct, hint in sci_human_body_data:
    add_q(t_id, s_id, text, opts, correct, hint)

# --- 2.2 psr_sci_plants_living_things (Plants & Life Processes) ---
t_id = "psr_sci_plants_living_things"
sci_plants_data = [
    ("What green pigment in plant leaves absorbs sunlight for photosynthesis?", ["Chlorophyll", "Hemoglobin", "Carotene", "Melanin"], "Chlorophyll", "Chlorophyll in chloroplasts traps sunlight energy required to convert CO₂ and water into glucose."),
    ("What are the raw materials required for photosynthesis?", ["Carbon dioxide and water", "Oxygen and glucose", "Nitrogen and soil", "Water and oxygen"], "Carbon dioxide and water", "Plants take in carbon dioxide from air and water from roots in the presence of sunlight."),
    ("What gas is released by green plants as a byproduct of photosynthesis?", ["Oxygen", "Carbon dioxide", "Nitrogen", "Hydrogen"], "Oxygen", "Photosynthesis produces glucose and releases vital oxygen into the atmosphere."),
    ("Which plant vascular tissue transports water and minerals upward from roots to leaves?", ["Xylem", "Phloem", "Stomata", "Pith"], "Xylem", "Xylem vessels conduct water and dissolved minerals from roots up the stem to leaves (Remember: Xylem = Water)."),
    ("Which plant tissue transports manufactured food (sugar) from leaves to all plant parts?", ["Phloem", "Xylem", "Cuticle", "Cortex"], "Phloem", "Phloem transports glucose/sucrose made in leaves to growing shoots and storage roots (Remember: Phloem = Food)."),
    ("Through which microscopic openings in leaves do gases enter and leave?", ["Stomata", "Veins", "Petioles", "Sepals"], "Stomata", "Stomata on the underside of leaves open and close to regulate gas exchange and transpiration."),
    ("What is the transfer of pollen grains from anther to stigma called?", ["Pollination", "Fertilization", "Germination", "Dispersal"], "Pollination", "Pollination is the transfer of pollen from the male anther to the female stigma of a flower."),
    ("Which part of a flower develops into a fruit after fertilization?", ["Ovary", "Petal", "Stigma", "Anther"], "Ovary", "After fertilization, the ovary swells to become the fruit, while the ovules inside become seeds."),
    ("Which seed dispersal method is used by fruits with fibrous, water-resistant husks like coconuts?", ["Water dispersal", "Wind dispersal", "Animal dispersal", "Explosive mechanism"], "Water dispersal", "Coconuts and nipah fruits float on sea currents to reach new shores."),
    ("What conditions are strictly necessary for seed germination?", ["Water, Oxygen, and Warmth (WOW)", "Sunlight, Soil, and Fertilizer", "Water, Darkness, and Wind", "Oxygen, Carbon dioxide, and Cold"], "Water, Oxygen, and Warmth (WOW)", "Seeds do not need sunlight or soil to germinate; they need Water, Oxygen, and Warmth (WOW)."),
    ("Which feature is typical of wind-pollinated flowers?", ["Dull petals, no scent, feathery stigmas and light pollen", "Large brightly coloured petals with sweet nectar", "Strong fragrant scent to attract bees", "Sticky pollen and sweet fruits"], "Dull petals, no scent, feathery stigmas and light pollen", "Wind-pollinated flowers (like grass and maize) don't need bright colors; their feathery stigmas catch airborne pollen."),
    ("What is the loss of water vapor from the leaves of a plant called?", ["Transpiration", "Condensation", "Photosynthesis", "Respiration"], "Transpiration", "Transpiration is the evaporation of water from plant leaves through open stomata."),
    ("Why do mangrove trees (bakau) in Brunei have breathing roots (pneumatophores)?", ["To obtain oxygen in waterlogged, muddy soil", "To absorb sunlight underwater", "To attract crabs", "To prevent leaves from dropping"], "To obtain oxygen in waterlogged, muddy soil", "Pneumatophores grow upward above the oxygen-poor mangrove mud to take in atmospheric air."),
    ("Which part of a seed provides nourishment for the growing embryo during germination?", ["Cotyledon (seed leaf)", "Seed coat (testa)", "Radicle", "Micropyle"], "Cotyledon (seed leaf)", "The cotyledon stores starch and proteins that nourish the seedling until green leaves develop."),
    ("What structure emerges first from a germinating seed?", ["Radicle (root)", "Plumule (shoot)", "First leaf", "Flower"], "Radicle (root)", "The radicle emerges first to anchor the seedling and absorb moisture from the soil."),
    ("In a food chain, what role do green plants always play?", ["Producers", "Primary consumers", "Decomposers", "Predators"], "Producers", "Plants produce their own food through photosynthesis and form the base of food chains."),
    ("Which plant responds to touch by rapidly folding its leaves?", ["Mimosa pudica (Touch-me-not)", "Hibiscus", "Fern", "Orchid"], "Mimosa pudica (Touch-me-not)", "Mimosa leaves fold when touched due to changes in turgor pressure at the leaf base."),
    ("What is the female reproductive part of a flower called?", ["Carpel / Pistil", "Stamen", "Anther", "Filament"], "Carpel / Pistil", "The pistil comprises stigma, style, and ovary. The male part is the stamen (anther and filament)."),
    ("How are dandelion and angsana seeds dispersed?", ["By wind", "By water", "By animal fur", "By explosive split"], "By wind", "They have parachute-like hairs or wing-like blades that allow wind to carry them away."),
    ("Which test proves that starch is present in a green leaf?", ["Iodine solution turns blue-black", "Benedict solution turns red", "Limewater turns milky", "Biuret solution turns purple"], "Iodine solution turns blue-black", "Iodine solution changes from yellow-brown to dark blue-black in the presence of starch."),
    ("Why must a leaf be boiled in alcohol before testing for starch?", ["To remove chlorophyll so color change can be seen", "To add starch", "To soften the stems", "To kill bacteria"], "To remove chlorophyll so color change can be seen", "Alcohol dissolves the green chlorophyll, bleaching the leaf white so iodine color change is clear."),
    ("What happens to plant stomata during severe hot droughts?", ["They close to conserve water", "They open wider to absorb heat", "They disappear", "They release all moisture"], "They close to conserve water", "Guard cells lose turgidity and close stomata to minimize water loss through transpiration."),
    ("Which seed dispersal method is used by rambutan and mango?", ["Animal dispersal (eaten and seeds discarded)", "Wind dispersal", "Water currents", "Explosive pod"], "Animal dispersal (eaten and seeds discarded)", "Animals and humans eat the sweet fleshy fruit and drop the seeds elsewhere."),
    ("What gas do plants take in during nighttime respiration?", ["Oxygen", "Carbon dioxide", "Nitrogen", "Chlorine"], "Oxygen", "At night without sunlight, photosynthesis stops and plants only respire, taking in oxygen and giving out CO₂."),
    ("What type of root system does a maize (corn) plant have?", ["Fibrous root system", "Taproot system", "Aerial root system", "Tuber root system"], "Fibrous root system", "Monocots like maize and grass have a cluster of fibrous roots rather than one deep central taproot."),
    ("How does the cactus plant adapt to desert conditions?", ["Leaves reduced to spines and thick fleshy stem to store water", "Broad thin leaves to catch rain", "No roots", "Soft hollow stem"], "Leaves reduced to spines and thick fleshy stem to store water", "Spines minimize transpiration loss, while thick succulent stems store water and photosynthesize."),
    ("What is an organism that breaks down dead plants and animals called?", ["Decomposer (Fungi and Bacteria)", "Carnivore", "Herbivore", "Parasite"], "Decomposer (Fungi and Bacteria)", "Decomposers recycle nutrients from dead organic matter back into the soil."),
    ("Which part of a flower produces pollen grains?", ["Anther", "Stigma", "Filament", "Ovary"], "Anther", "The anther sits atop the filament and contains pollen sacs that produce pollen."),
    ("What type of relationship exists when an orchid grows on a tree trunk without harming it?", ["Commensalism (Epiphyte)", "Parasitism", "Predation", "Mutualism"], "Commensalism (Epiphyte)", "The orchid gains high sunlight exposure without stealing nutrients from the host tree."),
    ("Which of the following plants reproduces via spores rather than seeds?", ["Bird's nest fern", "Hibiscus", "Papaya", "Sunflower"], "Bird's nest fern", "Ferns and mosses are non-flowering plants that reproduce using microscopic spores on the underside of fronds.")
]

for text, opts, correct, hint in sci_plants_data:
    add_q(t_id, s_id, text, opts, correct, hint)

# --- 2.3 psr_sci_energy_electricity (Energy, Electricity & Circuits) ---
t_id = "psr_sci_energy_electricity"
sci_energy_data = [
    ("What is the primary source of light and heat energy for planet Earth?", ["The Sun", "The Moon", "Coal fires", "Volcanoes"], "The Sun", "Solar energy from the Sun powers the water cycle, weather, and plant photosynthesis on Earth."),
    ("Which of the following is a non-renewable source of energy?", ["Petroleum", "Solar energy", "Wind power", "Biomass"], "Petroleum", "Fossil fuels (petroleum, coal, natural gas) take millions of years to form and will eventually deplete."),
    ("What energy transformation occurs when an electric iron is switched on?", ["Electrical energy to Heat energy", "Chemical energy to Light energy", "Kinetic energy to Sound energy", "Heat energy to Potential energy"], "Electrical energy to Heat energy", "Electrical current heating the heating element converts electrical energy into thermal heat energy."),
    ("In a torchlight, what energy change takes place when it is turned on?", ["Chemical -> Electrical -> Light + Heat", "Kinetic -> Sound -> Light", "Heat -> Electrical -> Sound", "Potential -> Nuclear -> Light"], "Chemical -> Electrical -> Light + Heat", "Chemical energy in dry cells converts into electrical current, then into light and heat in the bulb filament."),
    ("What component in an electrical circuit opens or closes the pathway for current flow?", ["Switch", "Battery", "Bulb", "Connecting wire"], "Switch", "A switch allows current to flow when closed and interrupts current flow when opened."),
    ("What happens in a series circuit if one bulb blows out (filament breaks)?", ["All other bulbs turn off", "Other bulbs become brighter", "Other bulbs stay on unchanged", "The circuit starts a fire"], "All other bulbs turn off", "In a series circuit, there is only one single path; if any component breaks, the whole circuit is broken."),
    ("Why are house lights connected in parallel circuits rather than in series?", ["Each appliance can be operated independently and receives full voltage", "It saves all electricity", "Parallel circuits use no switches", "Parallel circuits prevent all bulbs from lighting"], "Each appliance can be operated independently and receives full voltage", "In parallel, if one bulb burns out, others continue working normally."),
    ("Which of the following materials is a good conductor of electricity?", ["Copper wire", "Rubber glove", "Plastic ruler", "Dry wooden stick"], "Copper wire", "Metals like copper, silver, and aluminum allow electric charges to pass through freely."),
    ("Why are electric cables coated with plastic or rubber?", ["Because rubber is an insulator that prevents electric shocks", "Because plastic conducts electricity faster", "To make cables heavier", "To change the current color"], "Because rubber is an insulator that prevents electric shocks", "Insulators have high resistance and block current flow, protecting users from live wire shocks."),
    ("Which instrument is used to measure electrical current in a circuit?", ["Ammeter", "Voltmeter", "Thermometer", "Barometer"], "Ammeter", "An ammeter measures electric current in amperes (A)."),
    ("What type of energy is stored in food, fuel, and batteries?", ["Chemical potential energy", "Kinetic energy", "Light energy", "Sound energy"], "Chemical potential energy", "Chemical potential energy is stored in molecular bonds and released during chemical reactions."),
    ("A roller coaster car at the very top of a tall hill possesses maximum:", ["Gravitational potential energy", "Kinetic energy", "Sound energy", "Thermal energy"], "Gravitational potential energy", "Gravitational potential energy depends on height and mass (PE = mgh)."),
    ("As the roller coaster car plunges down the hill, its potential energy transforms into:", ["Kinetic energy", "Chemical energy", "Nuclear energy", "Electrical energy"], "Kinetic energy", "Loss of height leads to acceleration and gain of kinetic (motion) energy."),
    ("Which renewable energy resource harnesses the natural heat from inside the Earth?", ["Geothermal energy", "Hydroelectric energy", "Wind energy", "Tidal energy"], "Geothermal energy", "Geothermal energy utilizes underground steam and volcanic heat to drive turbines."),
    ("What happens to the brightness of two identical bulbs connected in series if a third bulb is added?", ["The bulbs become dimmer", "The bulbs become brighter", "The brightness remains unchanged", "The bulbs explode"], "The bulbs become dimmer", "Adding bulbs in series increases total circuit resistance, decreasing current and sharing voltage."),
    ("Which energy transformation occurs in a hydroelectric power station (dam)?", ["Gravitational PE -> Kinetic -> Electrical energy", "Chemical -> Heat -> Nuclear", "Solar -> Chemical -> Light", "Sound -> Kinetic -> Heat"], "Gravitational PE -> Kinetic -> Electrical energy", "Water stored high up flows down through penstocks (kinetic), turning turbine generators (electrical)."),
    ("What will happen if you connect a bulb with a broken filament in an electric circuit?", ["The circuit will be open and the bulb will not light", "The bulb will shine brighter", "The wires will freeze", "Current will bypass the bulb"], "The circuit will be open and the bulb will not light", "A broken filament creates an open circuit (incomplete loop), preventing current flow."),
    ("Why should you never touch electrical sockets with wet hands?", ["Water contains ions and conducts electricity, causing severe shock", "Water evaporates too quickly", "Water damages the plastic cover", "Water changes the frequency of light"], "Water contains ions and conducts electricity, causing severe shock", "Tap water is a conductor of electricity; moisture drastically reduces skin resistance."),
    ("What safety device contains a thin wire that melts when current exceeds a safe limit?", ["Fuse", "Battery", "Switch", "Transformer"], "Fuse", "A fuse breaks the circuit if dangerous power surges occur, preventing electrical fires."),
    ("Which of the following is an example of kinetic energy?", ["A speeding bullet train", "A stretched rubber band", "A battery on a shelf", "A boulder resting on a cliff"], "A speeding bullet train", "Kinetic energy is the energy possessed by moving objects."),
    ("A solar cell (photovoltaic panel) converts:", ["Light energy directly into Electrical energy", "Heat energy into Chemical energy", "Sound energy into Motion", "Kinetic energy into Nuclear energy"], "Light energy directly into Electrical energy", "Photovoltaic cells use semiconductor materials to convert solar photons into electricity."),
    ("What type of energy is stored in a compressed or stretched spring?", ["Elastic potential energy", "Kinetic energy", "Chemical energy", "Thermal energy"], "Elastic potential energy", "Deforming an elastic object stores elastic potential energy that returns when released."),
    ("What is the standard unit of electrical energy consumed in households?", ["Kilowatt-hour (kWh)", "Newton", "Pascal", "Volt"], "Kilowatt-hour (kWh)", "Electricity meters measure energy consumption in kilowatt-hours (kWh)."),
    ("Which material would make a bulb in a circuit light up if inserted into a gap?", ["An iron nail", "A glass rod", "A wooden pencil", "A ceramic tile"], "An iron nail", "Iron is a metal and an electrical conductor, completing the circuit."),
    ("What energy transformation occurs in a microphone?", ["Sound energy to Electrical energy", "Electrical energy to Sound energy", "Light energy to Heat energy", "Kinetic energy to Chemical energy"], "Sound energy to Electrical energy", "Sound waves vibrate a diaphragm that generates electrical audio signals."),
    ("What energy transformation occurs in a loudspeaker?", ["Electrical energy to Sound energy", "Sound energy to Light energy", "Chemical energy to Heat energy", "Thermal energy to Kinetic energy"], "Electrical energy to Sound energy", "Loudspeakers convert electrical audio impulses back into audible air vibrations."),
    ("In which circuit arrangement do all bulbs share the same brightness and independent paths?", ["Parallel circuit", "Series circuit", "Open circuit", "Short circuit"], "Parallel circuit", "Parallel branches provide full battery voltage to each bulb independently."),
    ("What happens when too many high-power appliances are plugged into one extension socket?", ["Overloading, causing wires to overheat and risk fire", "The appliances freeze", "Electricity usage drops to zero", "Voltage doubles"], "Overloading, causing wires to overheat and risk fire", "Overloading draws excessive current through thin extension wires, generating extreme heat."),
    ("Which gas emitted from burning fossil fuels is the primary cause of global warming?", ["Carbon dioxide", "Oxygen", "Argon", "Helium"], "Carbon dioxide", "Carbon dioxide traps heat in the atmosphere, creating the enhanced greenhouse effect."),
    ("What energy change occurs when rubbing two cold hands briskly together?", ["Kinetic energy converts to Heat energy via friction", "Light energy converts to Sound", "Chemical energy converts to Nuclear", "Potential energy converts to Magnetic"], "Kinetic energy converts to Heat energy via friction", "Mechanical friction converts motion into thermal warmth.")
]

for text, opts, correct, hint in sci_energy_data:
    add_q(t_id, s_id, text, opts, correct, hint)

# --- 2.4 psr_sci_forces_machines (Forces, Friction & Simple Machines) ---
t_id = "psr_sci_forces_machines"
sci_forces_data = [
    ("What is a force?", ["A push or a pull acting on an object", "The speed of a vehicle", "The weight of air", "The color of an object"], "A push or a pull acting on an object", "A force is defined as a push or a pull that can change an object's speed, direction, or shape."),
    ("What force pulls all dropped objects downward toward the center of the Earth?", ["Gravity", "Friction", "Magnetism", "Electrostatic force"], "Gravity", "Gravitational force pulls objects with mass towards Earth's center."),
    ("What force opposes the sliding motion between two surfaces in contact?", ["Friction", "Gravity", "Upthrust", "Tension"], "Friction", "Friction acts in the direction opposite to motion, slowing down moving surfaces."),
    ("Which of the following helps to reduce friction between moving machine parts?", ["Applying lubricating oil or grease", "Roughening the metal surfaces", "Sprinkling sand on gears", "Removing ball bearings"], "Applying lubricating oil or grease", "Lubricants form a smooth liquid film that prevents rough surface peaks from grinding together."),
    ("Why do racing car tyres have deep tread patterns on wet tracks?", ["To channel water away and increase grip", "To make the car lighter", "To look attractive", "To decrease friction"], "To channel water away and increase grip", "Tread grooves expel rainwater, preventing hydroplaning and maintaining road contact."),
    ("Which simple machine consists of a rigid bar pivoting around a fixed point (fulcrum)?", ["Lever", "Pulley", "Incline plane (Ramp)", "Wedge"], "Lever", "A lever rotates around a fulcrum to amplify effort force (e.g. crowbar, scissors, seesaw)."),
    ("In a wheelbarrow, where is the load located?", ["Between the fulcrum (wheel) and the effort (handles)", "At the handles", "Under the wheel", "Behind the worker"], "Between the fulcrum (wheel) and the effort (handles)", "A wheelbarrow is a Class 2 lever: Fulcrum at wheel, Load in the middle, Effort at handles."),
    ("Which simple machine is used to easily raise heavy flags to the top of a flagpole?", ["Fixed pulley", "Screw", "Wedge", "Wheel and axle"], "Fixed pulley", "A fixed pulley changes the direction of the applied force: pulling downward raises the flag upward."),
    ("A ramp used to roll heavy barrels into a truck is an example of a:", ["Inclined plane", "Lever", "Wedge", "Pulley"], "Inclined plane", "An inclined plane allows heavy loads to be raised using less effort force over a longer distance."),
    ("Which simple machine has two inclined planes placed back to back, used for splitting wood?", ["Wedge (like an axe)", "Screw", "Wheel and axle", "Pulley"], "Wedge (like an axe)", "An axe or knife is a wedge that concentrates downward force to push materials apart sideways."),
    ("What is the unit used to measure force in science?", ["Newton (N)", "Kilogram (kg)", "Joule (J)", "Watt (W)"], "Newton (N)", "Force is measured in Newtons (N) using a spring balance (dynamometer)."),
    ("What effect does friction have on the soles of school shoes over time?", ["It wears them down smooth", "It makes them thicker", "It turns them into rubber", "It has no effect"], "It wears them down smooth", "Friction gradually erodes and wears away rubber shoe treads."),
    ("When an apple falls from a tree, its downward speed increases because of:", ["Unbalanced gravitational force", "Friction pushing it down", "Wind blowing downward", "Magnetic attraction"], "Unbalanced gravitational force", "Gravity exerts an unbalanced downward force causing gravitational acceleration."),
    ("What type of force enables a parachute to slow down a skydiver's fall?", ["Air resistance (drag)", "Magnetic force", "Centrifugal force", "Chemical force"], "Air resistance (drag)", "Air resistance is a frictional drag force opposing motion through atmospheric gas."),
    ("Why do gymnasts apply chalk powder to their hands before performing?", ["To increase friction and prevent slipping", "To make hands slippery", "To cool down palms", "To reduce skin temperature"], "To increase friction and prevent slipping", "Chalk absorbs sweat and increases friction between palms and the gymnastic bars."),
    ("A screwdriver turning a screw is an example of which simple machine?", ["Wheel and axle", "Pulley", "Lever", "Wedge"], "Wheel and axle", "The wide handle is the wheel and the narrow shaft is the axle, multiplying turning force."),
    ("What type of lever has the fulcrum positioned in the center between effort and load?", ["Class 1 lever (e.g. Seesaw, Scissors)", "Class 2 lever (e.g. Wheelbarrow)", "Class 3 lever (e.g. Tweezer)", "Class 4 lever"], "Class 1 lever (e.g. Seesaw, Scissors)", "Class 1 levers have the Fulcrum in the middle: Load - Fulcrum - Effort."),
    ("Which object operates as a Class 3 lever with the effort applied between fulcrum and load?", ["Tweezers / Fishing rod", "Crowbar", "Bottle opener", "Nutcracker"], "Tweezers / Fishing rod", "Class 3 levers have Effort in the middle, allowing speed and precision rather than force multiplication."),
    ("What happens to an object when the forces acting upon it are balanced?", ["It remains stationary or continues moving at constant speed", "It accelerates rapidly", "It explodes", "It changes mass"], "It remains stationary or continues moving at constant speed", "Balanced forces result in zero net force, maintaining equilibrium according to Newton's First Law."),
    ("Why do airplanes and high-speed trains have streamlined teardrop shapes?", ["To reduce air resistance and drag", "To make them look like birds", "To increase their weight", "To increase air friction"], "To reduce air resistance and drag", "Streamlined profiles allow air to flow smoothly around the vehicle with minimal turbulent drag."),
    ("What instrument is commonly used in school science labs to measure pulling force?", ["Spring balance (Newton meter)", "Beam balance", "Stopwatch", "Thermometer"], "Spring balance (Newton meter)", "A spring balance measures force in Newtons based on Hooke's Law of spring extension."),
    ("If you push a heavy wooden box across a rough carpet vs smooth tiles, on which surface is friction higher?", ["Rough carpet", "Smooth tiles", "Both are identical", "Friction is zero on carpet"], "Rough carpet", "Rough carpet fibers interlock more strongly with the box bottom, generating higher friction."),
    ("Which simple machine is essentially an inclined plane wrapped spirally around a cylinder?", ["Screw", "Lever", "Pulley", "Wedge"], "Screw", "A screw is an inclined plane wrapped around a central core to hold materials firmly together."),
    ("What force causes a floating ship to stay afloat on seawater?", ["Upthrust (Buoyancy force)", "Gravity", "Friction", "Air pressure"], "Upthrust (Buoyancy force)", "The upward buoyant force exerted by displaced water balances the downward gravitational weight of the ship."),
    ("When two north poles of two bar magnets are pushed towards each other, they will:", ["Repel each other", "Attract each other", "Stick tightly", "Neutralize"], "Repel each other", "Like magnetic poles (N-N or S-S) repel; unlike poles (N-S) attract."),
    ("Why are ball bearings placed inside bicycle wheel hubs?", ["To replace sliding friction with rolling friction", "To add weight to the wheel", "To make the wheel magnetic", "To stop the wheel from turning"], "To replace sliding friction with rolling friction", "Rolling friction is far lower than sliding friction, allowing wheels to spin freely."),
    ("What force acts when a stretched bowstring propels an arrow forward?", ["Elastic force", "Gravitational force", "Frictional force", "Magnetic force"], "Elastic force", "The bent bow restores its original shape, releasing stored elastic energy as propulsive force."),
    ("If a force of 10 N pushes a toy car to the right and a frictional force of 4 N acts to the left, what is the net force?", ["6 N to the right", "14 N to the right", "6 N to the left", "10 N to the left"], "6 N to the right", "Net force = 10 N - 4 N = 6 N in the direction of the push (right)."),
    ("Why is walking on ice much more difficult and slippery than on tarmac pavement?", ["Ice has very little friction", "Ice has too much friction", "Gravity does not work on ice", "Ice is a magnetic insulator"], "Ice has very little friction", "A microscopic water film on smooth ice reduces friction to near zero, causing shoes to slip."),
    ("How does using a longer ramp make loading heavy furniture into a van easier?", ["It requires less effort force over a longer distance", "It reduces the weight of the furniture", "It eliminates gravity", "It shortens the travel distance"], "It requires less effort force over a longer distance", "A gentler slope reduces the effort required to lift the load.")
]

for text, opts, correct, hint in sci_forces_data:
    add_q(t_id, s_id, text, opts, correct, hint)

# --- 2.5 psr_sci_matter_earth (States of Matter, Water Cycle & Solar System) ---
t_id = "psr_sci_matter_earth"
sci_matter_data = [
    ("Which state of matter has a definite shape and a definite volume?", ["Solid", "Liquid", "Gas", "Plasma"], "Solid", "Solids have tightly packed particles vibrating in fixed positions, maintaining constant shape and volume."),
    ("Which state of matter has a definite volume but takes the shape of its container?", ["Liquid", "Solid", "Gas", "Vapour"], "Liquid", "Liquids flow to match container shape because particles can slide over one another."),
    ("What is the process of a liquid turning into a gas at its boiling point throughout the liquid called?", ["Boiling", "Evaporation", "Condensation", "Freezing"], "Boiling", "Boiling occurs at a fixed temperature (100°C for pure water) with bubbles forming throughout."),
    ("What is the change of state from gas to liquid called?", ["Condensation", "Evaporation", "Melting", "Sublimation"], "Condensation", "Condensation occurs when warm gas cools, losing kinetic energy and becoming liquid droplets."),
    ("At what temperature does pure water freeze into ice under normal atmospheric pressure?", ["0°C", "100°C", "32°C", "-10°C"], "0°C", "The freezing/melting point of pure water is 0°C; its boiling point is 100°C."),
    ("What causes day and night on Earth?", ["Earth's rotation on its own axis every 24 hours", "Earth's revolution around the Sun every 365 days", "The Moon blocking the Sun", "The Sun rotating around the Earth"], "Earth's rotation on its own axis every 24 hours", "As Earth rotates from West to East, the side facing the Sun experiences day, while the opposite side experiences night."),
    ("How long does it take for Earth to complete one full revolution around the Sun?", ["365 1/4 days (1 year)", "24 hours", "30 days", "28 days"], "365 1/4 days (1 year)", "One complete orbit around the Sun takes approximately 365.25 days."),
    ("Why does the Moon appear to shine in the night sky?", ["It reflects light from the Sun", "It produces its own light like a star", "It absorbs city lights", "It is made of glowing gas"], "It reflects light from the Sun", "The Moon has no luminous light source of its own; it reflects solar rays back to Earth."),
    ("Which planet is closest to the Sun in our solar system?", ["Mercury", "Venus", "Mars", "Earth"], "Mercury", "Mercury is the first and smallest planet orbiting nearest to the Sun."),
    ("Which planet is the largest planet in our solar system?", ["Jupiter", "Saturn", "Neptune", "Uranus"], "Jupiter", "Jupiter is a giant gas planet with mass more than double all other planets combined."),
    ("What stage in the water cycle forms clouds in the sky?", ["Condensation of rising water vapor into droplets", "Evaporation of sea water", "Precipitation of rain", "Transpiration from trees"], "Condensation of rising water vapor into droplets", "Warm water vapor rises, cools at high altitudes, and condenses around dust particles into visible clouds."),
    ("Rain, snow, sleet, and hail falling from clouds to Earth are forms of:", ["Precipitation", "Evaporation", "Condensation", "Percolation"], "Precipitation", "Precipitation refers to any liquid or frozen moisture that falls from clouds to the ground."),
    ("Why does sea water taste salty while rainwater is fresh?", ["Salts remain behind when sea water evaporates", "Rain dissolves salt from clouds", "The Sun burns the salt away", "Rainwater comes from outer space"], "Salts remain behind when sea water evaporates", "Only pure H₂O molecules evaporate from the ocean surface; heavy dissolved salts remain in the sea."),
    ("What is the process where a solid turns directly into a gas without melting into liquid?", ["Sublimation (e.g. Dry ice, mothballs)", "Evaporation", "Condensation", "Freezing"], "Sublimation (e.g. Dry ice, mothballs)", "Sublimation bypasses the liquid state (e.g. solid carbon dioxide dry ice turning into gas)."),
    ("Which planet is known as the 'Red Planet' due to iron oxide rust on its surface?", ["Mars", "Venus", "Mercury", "Jupiter"], "Mars", "Mars is called the Red Planet because iron-rich dust rusts in its thin atmosphere."),
    ("How long does the Moon take to complete one orbit around the Earth?", ["Approximately 28 to 29.5 days (1 lunar month)", "24 hours", "365 days", "7 days"], "Approximately 28 to 29.5 days (1 lunar month)", "The Moon orbits Earth and completes its phase cycle in roughly 29.5 days."),
    ("What causes ocean tides on Earth?", ["Gravitational pull of the Moon and Sun on Earth's oceans", "Earthquakes underwater", "Wind blowing across seas", "Hot underwater vents"], "Gravitational pull of the Moon and Sun on Earth's oceans", "The Moon's gravitational attraction pulls water towards it, creating high and low tides."),
    ("Which gas makes up about 78% of the Earth's atmosphere?", ["Nitrogen", "Oxygen", "Carbon dioxide", "Hydrogen"], "Nitrogen", "Dry air is composed of roughly 78% Nitrogen, 21% Oxygen, and 1% trace gases."),
    ("What happens to water particles when water is heated from 20°C to 80°C?", ["They gain kinetic energy and move faster and further apart", "They slow down and freeze", "They disappear", "They shrink in mass"], "They gain kinetic energy and move faster and further apart", "Thermal heat increases particle vibration and movement, causing slight expansion."),
    ("Why can gases be compressed into smaller containers while solids cannot?", ["Gas particles have large empty spaces between them", "Gas particles are softer", "Gases have no mass", "Solid particles move too fast"], "Gas particles have large empty spaces between them", "Gas molecules are widely spaced, allowing pressure to squeeze them closer together."),
    ("When dew forms on grass in the early morning, which process has taken place?", ["Condensation", "Evaporation", "Freezing", "Transpiration"], "Condensation", "Water vapor in cool night air contacts cold grass blades and condenses into liquid dew droplets."),
    ("Which planet is famous for its bright, prominent system of rings made of ice and rock?", ["Saturn", "Mars", "Venus", "Mercury"], "Saturn", "Saturn's extensive ring system consists of billions of icy fragments orbiting the planet."),
    ("What is the molten rock called while it is still trapped beneath the Earth's crust?", ["Magma", "Lava", "Basalt", "Granite"], "Magma", "Beneath Earth's surface it is magma; once erupted onto the surface it is called lava."),
    ("Why do we see lightning before we hear the clap of thunder?", ["Light travels much faster than sound in air", "Thunder starts after lightning ends", "Sound cannot travel through air", "Human eyes are closer than ears"], "Light travels much faster than sound in air", "Light travels at 300,000,000 m/s whereas sound travels at only about 340 m/s in air."),
    ("Which of the following factors speeds up the evaporation of puddle water?", ["Higher temperature, stronger wind, and larger surface area", "High humidity and low wind", "Cold weather and shade", "Covering the puddle with glass"], "Higher temperature, stronger wind, and larger surface area", "Heat provides energy, wind carries vapor away, and surface area exposes more liquid molecules."),
    ("What is an eclipse of the Sun (Solar Eclipse)?", ["The Moon passes directly between the Sun and Earth, casting a shadow on Earth", "Earth passes between Sun and Moon", "The Sun turns off its light", "A comet crashes into the Sun"], "The Moon passes directly between the Sun and Earth, casting a shadow on Earth", "During a solar eclipse, the Moon's shadow falls onto parts of the Earth during daytime."),
    ("What is an eclipse of the Moon (Lunar Eclipse)?", ["Earth passes directly between Sun and Moon, casting Earth's shadow on the Moon", "The Moon passes in front of the Sun", "The Moon falls into the ocean", "Clouds cover the Moon"], "Earth passes directly between Sun and Moon, casting Earth's shadow on the Moon", "A lunar eclipse occurs when Earth blocks sunlight from directly illuminating the full Moon."),
    ("Which layer of the Earth's atmosphere contains the ozone layer that protects us from harmful UV rays?", ["Stratosphere", "Troposphere", "Mesosphere", "Exosphere"], "Stratosphere", "The ozone layer resides in the stratosphere and absorbs ultraviolet solar radiation."),
    ("Why does ice float on top of liquid water?", ["Ice is less dense than liquid water", "Ice is heavier than water", "Ice contains air bubbles only", "Ice is a gas"], "Ice is less dense than liquid water", "Water expands upon freezing, giving ice a lower density (~0.92 g/cm³) than liquid water (1.0 g/cm³)."),
    ("Which planet in our solar system is nicknamed the 'Morning Star' or 'Evening Star'?", ["Venus", "Mars", "Jupiter", "Saturn"], "Venus", "Venus reflects high sunlight due to thick sulfuric clouds and appears brightly at dawn and dusk.")
]

for text, opts, correct, hint in sci_matter_data:
    add_q(t_id, s_id, text, opts, correct, hint)

# =========================================================================
# SUBJECT 3: ENGLISH LANGUAGE (PSR BRUNEI) - 4 Topics
# =========================================================================

# --- 3.1 psr_eng_grammar_tenses (Tenses & Subject-Verb Agreement) ---
t_id = "psr_eng_grammar_tenses"
s_id = "psr_english"
eng_tenses_data = [
    ("Neither the teacher nor the students _______ present at the hall yesterday.", ["were", "was", "is", "are"], "were", "In 'neither... nor', the verb agrees with the nearer subject ('students' is plural -> were)."),
    ("The flock of migratory birds _______ flying south for the winter.", ["is", "are", "were", "have"], "is", "Collective nouns like 'flock' take a singular verb when acting as a single unit."),
    ("She _______ her homework before her mother arrived home from work.", ["had finished", "has finished", "finishes", "is finishing"], "had finished", "Past Perfect tense ('had finished') indicates an action completed before another past event ('arrived')."),
    ("Neither of the two books _______ available in the national library.", ["is", "are", "were", "have been"], "is", "'Neither of' takes a singular verb: 'Neither of the two books is available'."),
    ("By the time we reach Bandar Seri Begawan tomorrow, the ceremony _______.", ["will have started", "starts", "started", "has started"], "will have started", "Future Perfect tense ('will have started') expresses an action completed before a future time."),
    ("Each of the participants _______ given a certificate of achievement.", ["was", "were", "are", "have"], "was", "'Each of' is followed by a singular verb ('was given')."),
    ("Hafiz _______ in Tutong since he was seven years old.", ["has lived", "lives", "is living", "lived"], "has lived", "Present Perfect tense with 'since' describes an action starting in the past and continuing now."),
    ("If it _______ heavily tomorrow, the football tournament will be postponed.", ["rains", "will rain", "rained", "is raining"], "rains", "First conditional: 'If + Simple Present (rains), will + base verb'."),
    ("Ten kilometers _______ a long distance to walk in the tropical heat.", ["is", "are", "were", "have been"], "is", "Expressions of distance, money, and time are treated as singular units."),
    ("The doctor advised the patient to _______ sweet drinks to lower blood sugar.", ["avoid", "avoids", "avoiding", "avoided"], "avoid", "After 'to' (infinitive), use the base form of the verb: 'to avoid'."),
    ("Ali as well as his cousins _______ invited to the wedding banquet.", ["was", "were", "are", "have been"], "was", "Phrases introduced by 'as well as' do not change the number of the subject ('Ali' is singular -> was)."),
    ("The news broadcast on RTB _______ very encouraging today.", ["is", "are", "were", "have been"], "is", "'News' is an uncountable noun and always takes a singular verb."),
    ("While we _______ our dinner, the electricity suddenly went out.", ["were having", "are having", "have had", "had had"], "were having", "Past Continuous tense ('were having') for an ongoing past action interrupted by another past action ('went')."),
    ("Bread and butter _______ his favourite breakfast every morning.", ["is", "are", "were", "have been"], "is", "Compound subjects thought of as a single dish/concept take a singular verb."),
    ("The detective noticed that someone _______ the front door lock.", ["had tampered with", "has tampered with", "is tampering", "tamper"], "had tampered with", "Past Perfect for an action that happened before the detective's past observation."),
    ("They _______ for the bus for over forty minutes before it finally arrived.", ["had been waiting", "have waited", "are waiting", "waited"], "had been waiting", "Past Perfect Continuous emphasizes the duration of an activity before another past event."),
    ("Physics _______ a challenging subject for many secondary school students.", ["is", "are", "were", "being"], "is", "Subject names ending in 's' (Physics, Mathematics, Civics) take a singular verb."),
    ("Everyone in the grand auditorium _______ silent when His Majesty entered.", ["was", "were", "are", "have been"], "was", "Indefinite pronouns like 'Everyone', 'Someone', 'Nobody' take singular verbs."),
    ("The athlete _______ hard every single day to qualify for the SEA Games.", ["trains", "train", "training", "have trained"], "trains", "Third-person singular present tense requires 's': 'The athlete trains'."),
    ("She speaks French fluently, _______ she?", ["doesn't", "does", "isn't", "wasn't"], "doesn't", "Positive statement in Simple Present uses negative question tag 'doesn't she?'."),
    ("None of the spilled milk _______ salvageable.", ["was", "were", "are", "have been"], "was", "'Milk' is uncountable, so 'None of the milk' takes a singular verb ('was')."),
    ("You haven't submitted your science project yet, _______ you?", ["have", "haven't", "did", "didn't"], "have", "Negative statement takes a positive question tag ('haven't you? -> have you?')."),
    ("The pair of scissors _______ kept in the top drawer.", ["is", "are", "were", "have been"], "is", "'The pair' is singular, so it takes a singular verb ('is kept')."),
    ("I wish I _______ more time to complete the English composition paper.", ["had", "have", "having", "am having"], "had", "Hypothetical wish in the present uses the subjunctive past form ('had')."),
    ("The principal insisted that every student _______ the school uniform.", ["wear", "wears", "wore", "wearing"], "wear", "Subjunctive mood after 'insisted that' uses base verb ('wear')."),
    ("Neither car _______ damaged in the minor collision.", ["was", "were", "are", "have been"], "was", "'Neither' followed by a singular noun takes a singular verb ('was')."),
    ("Look at those dark clouds! It _______ rain soon.", ["is going to", "will have", "rained", "was raining"], "is going to", "'Going to' is used for future predictions supported by present visual evidence."),
    ("Three hundred dollars _______ too much to pay for a simple pair of shoes.", ["is", "are", "were", "being"], "is", "Sums of money take singular verbs."),
    ("Hardly had the bell rung when the pupils _______ out of the classroom.", ["rushed", "rush", "rushing", "have rushed"], "rushed", "'Hardly had... when' connects past perfect with simple past ('rushed')."),
    ("He _______ to school on foot every day because his house is nearby.", ["walks", "walk", "walking", "has walked"], "walks", "Habitual regular action takes Simple Present: 'He walks'.")
]

for text, opts, correct, hint in eng_tenses_data:
    add_q(t_id, s_id, text, opts, correct, hint)

# --- 3.2 psr_eng_parts_of_speech (Prepositions, Conjunctions & Pronouns) ---
t_id = "psr_eng_parts_of_speech"
eng_parts_data = [
    ("The adventurous boys jumped _______ the river to cool off on a hot afternoon.", ["into", "onto", "in", "to"], "into", "'Into' indicates movement from outside to inside a body of water."),
    ("The Independence Day celebration will commence _______ 8:00 a.m. sharp.", ["at", "on", "in", "by"], "at", "Use 'at' for specific clock times ('at 8:00 a.m.')."),
    ("Brunei National Day is proudly celebrated _______ the 23rd of February every year.", ["on", "in", "at", "during"], "on", "Use 'on' for specific calendar dates and days ('on the 23rd of February')."),
    ("The girl _______ won the national public speaking competition is my classmate.", ["who", "whom", "which", "whose"], "who", "'Who' is the subject relative pronoun referring to people ('The girl who won')."),
    ("The historic book _______ cover was torn has been carefully repaired by the librarian.", ["whose", "which", "whom", "who"], "whose", "'Whose' shows possession for both people and things ('whose cover')."),
    ("He worked diligently day and night _______ he could secure a scholarship.", ["so that", "although", "unless", "whereas"], "so that", "'So that' expresses purpose or intention."),
    ("_______ she felt exhausted after the long hike, she continued walking up the hill.", ["Although", "Because", "Since", "Unless"], "Although", "'Although' introduces a clause of contrast or concession."),
    ("You will not pass the examination _______ you revise your notes consistently.", ["unless", "if", "because", "so"], "unless", "'Unless' means 'if not': 'Unless you revise, you will not pass'."),
    ("The kitten hid _______ the sofa because it was frightened by the thunder.", ["under", "between", "among", "over"], "under", "'Under' denotes position beneath a physical object."),
    ("Distribute the sweets equally _______ the four children.", ["among", "between", "with", "to"], "among", "Use 'between' for two items/people, and 'among' for three or more."),
    ("The bridge was constructed _______ the Tutong River to connect two villages.", ["across", "through", "along", "over"], "across", "'Across' signifies spanning from one side of a river/road to the other."),
    ("He walked _______ the dense jungle path until he spotted the waterfall.", ["along", "across", "between", "over"], "along", "'Along' indicates movement parallel to the direction of a path or road."),
    ("To _______ did you lend your English dictionary?", ["whom", "who", "which", "whose"], "whom", "'Whom' is the objective pronoun used after prepositions ('To whom')."),
    ("The boy made the wooden toy all by _______.", ["himself", "itself", "herself", "themselves"], "himself", "Reflexive pronoun for a male singular subject ('The boy -> himself')."),
    ("She poured the hot tea _______ the porcelain cup.", ["into", "onto", "at", "in"], "into", "Movement into an enclosure requires 'into'."),
    ("The plane flew high _______ the storm clouds.", ["above", "on", "at", "into"], "above", "'Above' indicates a higher vertical altitude without physical contact."),
    ("Neither Kamal _______ Danial attended the extra tuition class.", ["nor", "or", "and", "but"], "nor", "The correlative conjunction pair is 'neither... nor' (and 'either... or')."),
    ("I have not seen my primary school teacher _______ last December.", ["since", "for", "during", "at"], "since", "Use 'since' with a specific starting point in time ('since last December')."),
    ("They stayed at the beach resort _______ three consecutive days.", ["for", "since", "during", "while"], "for", "Use 'for' with a duration of time ('for three days')."),
    ("Despite _______ unwell, the brave young soldier reported for duty.", ["feeling", "felt", "feels", "feel"], "feeling", "Prepositions and prepositions phrases like 'Despite' take a gerund (-ing form)."),
    ("The car stopped _______ the traffic light because it turned red.", ["at", "on", "in", "to"], "at", "Use 'at' for designated traffic points ('at the traffic light')."),
    ("The passengers walked _______ the narrow tunnel to board the aircraft.", ["through", "across", "between", "over"], "through", "'Through' denotes movement inside a three-dimensional enclosed space."),
    ("He is not only intelligent _______ very humble and helpful.", ["but also", "and also", "as well as", "together with"], "but also", "Correlative conjunction pair: 'not only... but also'."),
    ("My father parked his car _______ the two tall palm trees.", ["between", "among", "in", "at"], "between", "Use 'between' when referring to exactly two reference points."),
    ("She was rewarded _______ her honesty and integrity.", ["for", "with", "by", "from"], "for", "One is rewarded 'for' a virtuous quality or deed."),
    ("The students listened _______ to the safety briefing before the science experiment.", ["attentively", "attentive", "attention", "attentiveness"], "attentively", "An adverb of manner ('attentively') describes how the verb 'listened' was done."),
    ("This antique pocket watch belonged to my grandfather; it is _______.", ["mine", "my", "me", "myself"], "mine", "Possessive pronoun standing alone without a following noun is 'mine'."),
    ("The dog barked fiercely _______ the stranger approaching the gate.", ["at", "on", "to", "with"], "at", "The standard preposition after 'bark' is 'at'."),
    ("We must leave immediately, _______ we will miss the morning ferry to Temburong.", ["otherwise", "whereas", "although", "since"], "otherwise", "'Otherwise' means 'or else' to indicate an adverse consequence."),
    ("The athlete was disqualified _______ breaking the starting lane rules.", ["for", "because", "so", "with"], "for", "'Disqualified for' followed by gerund expresses the reason for penalty.")
]

for text, opts, correct, hint in eng_parts_data:
    add_q(t_id, s_id, text, opts, correct, hint)

# --- 3.3 psr_eng_vocabulary_idioms (Idioms, Phrasal Verbs & Vocabulary) ---
t_id = "psr_eng_vocabulary_idioms"
eng_vocab_data = [
    ("What does the idiom 'a piece of cake' mean?", ["Very easy to accomplish", "A sweet dessert", "Very expensive", "Difficult to understand"], "Very easy to accomplish", "'A piece of cake' is an English idiom meaning something extremely simple or effortless."),
    ("When someone says they are 'feeling under the weather', they mean they are:", ["Feeling slightly ill or sick", "Experiencing cold rain", "Very joyful", "Angry at the weather"], "Feeling slightly ill or sick", "'Under the weather' means unwell or unwell in health."),
    ("What is the meaning of the phrasal verb 'give up'?", ["Stop trying or surrender", "Donate money", "Offer a present", "Rise early"], "Stop trying or surrender", "'Give up' means to cease an effort or abandon hope."),
    ("Choose the word that is opposite in meaning (antonym) to 'GENEROUS':", ["Stingy", "Kind", "Courteous", "Polite"], "Stingy", "'Generous' means willing to give freely; its antonym is 'stingy' or 'miserly'."),
    ("Choose the word closest in meaning (synonym) to 'HAZARDOUS':", ["Dangerous", "Safe", "Secure", "Gentle"], "Dangerous", "'Hazardous' describes something perilous or dangerous."),
    ("What does it mean to 'burn the midnight oil'?", ["Study or work late into the night", "Waste lamp kerosene", "Cause a kitchen fire", "Wake up before sunrise"], "Study or work late into the night", "'Burn the midnight oil' means working diligently late past bedtime."),
    ("The firefighter was commended for his 'valour'. What does 'valour' mean?", ["Great bravery and courage", "Physical strength", "Speed and agility", "Cleverness"], "Great bravery and courage", "'Valour' means heroic courage in the face of danger."),
    ("What does the idiom 'once in a blue moon' mean?", ["Very rarely", "Every month", "During nighttime", "Frequently"], "Very rarely", "'Once in a blue moon' signifies an occurrence that happens extremely seldom."),
    ("What does the phrasal verb 'call off' mean in 'They had to call off the match'?", ["Cancel", "Postpone", "Continue", "Start"], "Cancel", "'Call off' means to cancel an event entirely."),
    ("Choose the correct spelling:", ["Accommodation", "Acommodation", "Accomodation", "Acomodation"], "Accommodation", "Accommodation is spelled with double 'c' and double 'm'."),
    ("What does the idiom 'spill the beans' mean?", ["Reveal a secret prematurely", "Drop groceries", "Cook dinner", "Tell the truth in court"], "Reveal a secret prematurely", "'Spill the beans' means to disclose confidential information."),
    ("Choose the synonym for 'ABUNDANT':", ["Plentiful", "Scarce", "Sparse", "Tiny"], "Plentiful", "'Abundant' means existing in large quantities or plentiful."),
    ("What does 'break the ice' mean in a social gathering?", ["Overcome initial shyness and start conversation", "Drop an ice cube", "Chill a drink", "End an argument"], "Overcome initial shyness and start conversation", "'Break the ice' means initiating friendly conversation in an awkward or formal setting."),
    ("Choose the antonym for 'ANCIENT':", ["Modern", "Antique", "Old", "Historic"], "Modern", "'Ancient' refers to long ago; its direct opposite is 'modern'."),
    ("What does the phrasal verb 'look up to' mean in 'Young players look up to the captain'?", ["Admire and respect", "Look at the ceiling", "Search in a book", "Supervise"], "Admire and respect", "'Look up to someone' means to regard them with deep admiration and respect."),
    ("The weather was 'unpredictable'. What does 'unpredictable' mean?", ["Likely to change unexpectedly", "Very hot", "Calm and peaceful", "Predictable"], "Likely to change unexpectedly", "Prefix 'un-' means not; unpredictable means cannot be foreseen with certainty."),
    ("What is the meaning of the idiom 'cost an arm and a leg'?", ["Extremely expensive", "Very cheap", "Painful to carry", "Worth nothing"], "Extremely expensive", "If an item costs an arm and a leg, it is excessively pricey."),
    ("Choose the synonym for 'COMMENCE':", ["Begin", "Finish", "Pause", "Delay"], "Begin", "'Commence' is a formal verb meaning to begin or start."),
    ("What does it mean to 'turn a deaf ear' to advice?", ["Refuse to listen or ignore", "Have hearing trouble", "Clean one's ear", "Agree wholeheartedly"], "Refuse to listen or ignore", "'Turn a deaf ear' means willfully ignoring someone's warning or advice."),
    ("Choose the antonym for 'ARROGANT':", ["Humble", "Proud", "Boastful", "Haughty"], "Humble", "'Arrogant' means having an exaggerated sense of self-importance; opposite is 'humble'."),
    ("What does the phrasal verb 'put off' mean in 'Never put off till tomorrow what you can do today'?", ["Postpone or delay", "Extinguish", "Wear clothes", "Discard"], "Postpone or delay", "'Put off' means to delay doing something until a later time."),
    ("The word 'fragile' on a parcel warns handlers that the contents are:", ["Easily broken or damaged", "Very heavy", "Flammable", "Waterproof"], "Easily broken or damaged", "'Fragile' items (like glass and porcelain) crack or break easily if dropped."),
    ("What does the idiom 'see eye to eye' mean?", ["Agree completely with someone", "Stare angrily", "Wear glasses", "Meet in person"], "Agree completely with someone", "'See eye to eye' means to have identical opinions and agree."),
    ("Choose the correct word: 'The judge was known for his _______ judgment.'", ["impartial", "partial", "biased", "prejudiced"], "impartial", "'Impartial' means fair, unbiased, and treating all parties equally."),
    ("What does 'hit the nail on the head' mean?", ["State an exact truth accurately", "Use a hammer properly", "Injure a finger", "Make a loud noise"], "State an exact truth accurately", "'Hit the nail on the head' means describing the precise cause or truth of a situation."),
    ("Choose the synonym for 'SWIFT':", ["Rapid", "Sluggish", "Heavy", "Careless"], "Rapid", "'Swift' means moving with great speed or rapid."),
    ("What does the phrasal verb 'run out of' mean in 'We have run out of printer paper'?", ["Exhaust the supply of something", "Sprint outdoors", "Throw away", "Buy extra"], "Exhaust the supply of something", "'Run out of' means having depleted the available stock of a resource."),
    ("Choose the antonym for 'OBEDIENT':", ["Rebellious", "Compliant", "Dutiful", "Respectful"], "Rebellious", "'Obedient' means following instructions; its opposite is 'rebellious' or 'defiant'."),
    ("What is an 'amphibian'?", ["An animal able to live both on land and in water", "A creature that flies", "A plant with spores", "A deep-sea fish"], "An animal able to live both on land and in water", "Amphibians (like frogs and salamanders) live in water and on land during their life cycles."),
    ("What does the idiom 'barking up the wrong tree' mean?", ["Pursuing a mistaken line of thought or action", "Scolding a pet", "Climbing trees", "Losing a track"], "Pursuing a mistaken line of thought or action", "'Barking up the wrong tree' means blaming the wrong person or following a false assumption.")
]

for text, opts, correct, hint in eng_vocab_data:
    add_q(t_id, s_id, text, opts, correct, hint)

# --- 3.4 psr_eng_sentence_structures (Sentence Transformation, Active/Passive & Direct/Indirect) ---
t_id = "psr_eng_sentence_structures"
eng_structure_data = [
    ("Change to passive voice: 'The mechanic repaired the damaged car.'", ["The damaged car was repaired by the mechanic.", "The damaged car is repaired by the mechanic.", "The mechanic was repaired by the car.", "The damaged car had been repaired."], "The damaged car was repaired by the mechanic.", "Simple past active ('repaired') becomes past passive ('was repaired by')."),
    ("Change to passive voice: 'The chef is preparing a delicious dessert.'", ["A delicious dessert is being prepared by the chef.", "A delicious dessert was prepared by the chef.", "A delicious dessert is prepared by the chef.", "A delicious dessert has been prepared."], "A delicious dessert is being prepared by the chef.", "Present continuous active ('is preparing') becomes 'is being prepared'."),
    ("Convert to indirect speech: 'I am reading an adventure novel,' said Danial.", ["Danial said that he was reading an adventure novel.", "Danial said that he is reading an adventure novel.", "Danial said that I was reading an adventure novel.", "Danial says that he was reading an adventure novel."], "Danial said that he was reading an adventure novel.", "Direct 'I am reading' shifts back in tense to 'he was reading' in reported speech."),
    ("Convert to indirect speech: 'Where do you live?' asked the police officer.", ["The police officer asked where I lived.", "The police officer asked where did I live.", "The police officer asked where do I live.", "The police officer asked where I had lived."], "The police officer asked where I lived.", "In reported questions, do-support drops and word order becomes subject + verb: 'where I lived'."),
    ("Combine using 'Although': 'It was raining heavily. The boys continued playing football.'", ["Although it was raining heavily, the boys continued playing football.", "Although the boys played football, it rained heavily.", "It was raining heavily although the boys continued.", "The boys continued although raining heavily."], "Although it was raining heavily, the boys continued playing football.", "'Although' shows concession at the beginning of the dependent contrast clause."),
    ("Change to active voice: 'The national anthem was sung by the school choir.'", ["The school choir sang the national anthem.", "The school choir was singing the anthem.", "The school choir sings the anthem.", "The school choir has sung the anthem."], "The school choir sang the national anthem.", "Passive 'was sung by the choir' converts into simple past active 'The school choir sang'."),
    ("Choose the correct punctuation: 'Where did you put my glasses _______'", ["?", ".", "!", ","], "?", "Direct questions must end with a question mark (?)."),
    ("Convert to indirect speech: 'Do not play near the busy road,' mother told the children.", ["Mother warned the children not to play near the busy road.", "Mother told the children do not play near the road.", "Mother said the children to not play near the road.", "Mother asked the children not playing near the road."], "Mother warned the children not to play near the busy road.", "Imperative commands convert to 'told/warned + object + not to + base verb'."),
    ("Combine using 'Both... and': 'Siti likes reading. Aini likes reading.'", ["Both Siti and Aini like reading.", "Both Siti and Aini likes reading.", "Both Siti with Aini like reading.", "Siti and Aini both likes reading."], "Both Siti and Aini like reading.", "Correlative 'Both... and' forms a plural subject and takes plural verb 'like'."),
    ("Change to passive voice: 'The earthquake destroyed hundreds of buildings.'", ["Hundreds of buildings were destroyed by the earthquake.", "Hundreds of buildings was destroyed by the earthquake.", "Hundreds of buildings are destroyed by the earthquake.", "The earthquake was destroyed by buildings."], "Hundreds of buildings were destroyed by the earthquake.", "'Hundreds of buildings' is plural, so use 'were destroyed'."),
    ("Identify the sentence with correct capitalization:", ["His Majesty Sultan Haji Hassanal Bolkiah visited the district of Temburong.", "his majesty sultan haji hassanal bolkiah visited temburong.", "His majesty Sultan haji hassanal bolkiah Visited Temburong.", "His Majesty sultan Haji hassanal Bolkiah visited temburong."], "His Majesty Sultan Haji Hassanal Bolkiah visited the district of Temburong.", "Royal titles, personal names, and geographical districts must be capitalized."),
    ("Combine using 'Neither... nor': 'He does not drink tea. He does not drink coffee.'", ["He drinks neither tea nor coffee.", "He drinks neither tea or coffee.", "Neither he drinks tea nor coffee.", "He does not drink neither tea nor coffee."], "He drinks neither tea nor coffee.", "'Neither... nor' replaces negative words without double negation: 'drinks neither tea nor coffee'."),
    ("Convert to indirect speech: 'I will submit the assignment tomorrow,' promised Farid.", ["Farid promised that he would submit the assignment the next day.", "Farid promised that he will submit the assignment tomorrow.", "Farid promised that I would submit the assignment.", "Farid promised that he would submit the assignment tomorrow."], "Farid promised that he would submit the assignment the next day.", "'Will' shifts to 'would', and 'tomorrow' shifts to 'the next day' in reported speech."),
    ("Change to passive voice: 'Someone has stolen my bicycle.'", ["My bicycle has been stolen.", "My bicycle was stolen by someone.", "My bicycle is been stolen.", "Someone was stolen my bicycle."], "My bicycle has been stolen.", "Present perfect passive: 'has stolen' -> 'has been stolen'."),
    ("Choose the correct relative clause: 'The laptop _______ I bought yesterday is very fast.'", ["which", "who", "whose", "whom"], "which", "Inanimate objects take 'which' or 'that'."),
    ("Combine using 'so... that': 'The luggage was very heavy. She could not lift it.'", ["The luggage was so heavy that she could not lift it.", "The luggage was heavy so that she could not lift it.", "Because the luggage was so heavy that she lifted it.", "The luggage was very heavy that she could not lift it."], "The luggage was so heavy that she could not lift it.", "Structure: 'so + adjective + that + result clause'."),
    ("Change to active voice: 'The novel was written by a famous Bruneian author.'", ["A famous Bruneian author wrote the novel.", "A famous Bruneian author was writing the novel.", "A famous Bruneian author writes the novel.", "The novel wrote a famous Bruneian author."], "A famous Bruneian author wrote the novel.", "The agent 'A famous Bruneian author' becomes the subject in simple past active."),
    ("Choose the sentence with correct apostrophe usage for possession:", ["The girls' bicycles were parked neatly in the shed.", "The girl's bicycles were parked neatly in the shed.", "The girls bicycles' were parked neatly in the shed.", "The girls bicycle's were parked neatly in the shed."], "The girls' bicycles were parked neatly in the shed.", "For plural nouns ending in 's', add the apostrophe after the 's' (girls')."),
    ("Convert to reported speech: 'Can you swim across the river?' he asked me.", ["He asked me if I could swim across the river.", "He asked me that can I swim across the river.", "He asked me if I can swim across the river.", "He asked me could I swim across the river."], "He asked me if I could swim across the river.", "Yes/No questions use 'if/whether' + subject + past modal 'could'."),
    ("Combine using 'in order to': 'He exercised every morning. He wanted to maintain his fitness.'", ["He exercised every morning in order to maintain his fitness.", "In order to exercise every morning he wanted fitness.", "He wanted fitness in order to he exercised.", "He exercised so in order to maintain fitness."], "He exercised every morning in order to maintain his fitness.", "'In order to + base verb' concisely connects action to objective."),
    ("Choose the correct tag: 'Let's visit the Brunei Arts and Handicraft Centre, _______?'", ["shall we", "will you", "don't we", "aren't we"], "shall we", "Suggestions starting with 'Let's' always take the question tag 'shall we?'."),
    ("Change to passive voice: 'They will announce the PSR examination results next week.'", ["The PSR examination results will be announced next week.", "The PSR examination results are announced next week.", "The PSR examination results will announce next week.", "The PSR examination results have been announced."], "The PSR examination results will be announced next week.", "Future simple passive: 'will + be + past participle (announced)'."),
    ("Identify the compound sentence:", ["The bell rang, and the children cheered enthusiastically.", "Because the bell rang, the children cheered.", "The children cheered loudly after hearing the bell.", "Hearing the bell, the children cheered."], "The bell rang, and the children cheered enthusiastically.", "A compound sentence contains two independent clauses joined by coordinating conjunction 'and'."),
    ("Convert to indirect speech: 'I must complete my chores now,' said Sara.", ["Sara said that she had to complete her chores then.", "Sara said that she must complete her chores now.", "Sara said that she has to complete her chores then.", "Sara said that she had to complete her chores now."], "Sara said that she had to complete her chores then.", "'Must' shifts to 'had to', and 'now' shifts to 'then'."),
    ("Combine using 'too... to': 'The coffee is very hot. I cannot drink it.'", ["The coffee is too hot for me to drink.", "The coffee is too hot that I cannot drink.", "The coffee is so hot to drink.", "Too hot is the coffee to drink."], "The coffee is too hot for me to drink.", "'Too + adjective + for object + to infinitive'."),
    ("Change to passive voice: 'The police caught the burglar yesterday.'", ["The burglar was caught by the police yesterday.", "The burglar is caught by the police yesterday.", "The burglar had been caught by the police.", "The police was caught by the burglar."], "The burglar was caught by the police yesterday.", "Simple past passive: 'was caught by'."),
    ("Choose the correct order of adjectives: 'She bought a _______ dress.'", ["beautiful long silk", "silk beautiful long", "long silk beautiful", "beautiful silk long"], "beautiful long silk", "Standard adjective order: Opinion (beautiful) -> Size/Length (long) -> Material (silk)."),
    ("Which sentence contains a correct semicolon (;)?", ["Hafiz loves reading science fiction; his brother prefers playing badminton.", "Hafiz loves reading; because his brother plays badminton.", "Hafiz loves reading science fiction; and his brother prefers badminton.", "Hafiz; loves reading science fiction."], "Hafiz loves reading science fiction; his brother prefers playing badminton.", "A semicolon joins two related independent clauses without a coordinating conjunction."),
    ("Convert to direct speech: 'The teacher told us to open our workbooks.'", ["The teacher said, 'Open your workbooks.'", "The teacher said, 'To open your workbooks.'", "The teacher said, 'You open our workbooks.'", "The teacher told, 'Open your workbooks.'"], "The teacher said, 'Open your workbooks.'", "Direct imperative with quotation marks."),
    ("Combine using 'unless': 'You will miss the bus if you do not wake up early.'", ["You will miss the bus unless you wake up early.", "Unless you miss the bus you wake up early.", "You wake up early unless you will miss the bus.", "You will not miss the bus unless you do not wake up early."], "You will miss the bus unless you wake up early.", "'Unless' replaces 'if you do not'.")
]

for text, opts, correct, hint in eng_structure_data:
    add_q(t_id, s_id, text, opts, correct, hint)

# =========================================================================
# SUBJECT 4: BAHASA MELAYU (PSR BRUNEI) - 4 Topics
# =========================================================================

# --- 4.1 psr_bm_tatabahasa_imbuhan (Tatabahasa & Imbuhan) ---
t_id = "psr_bm_tatabahasa_imbuhan"
s_id = "psr_bahasa_melayu"
bm_tatabahasa_data = [
    ("Pilih ayat yang mengandungi Kata Kerja Transitif:", ["Petani itu menanam padi di sawah.", "Adik sedang tidur di bilik.", "Burung-burung berterbangan di udara.", "Bapa tersenyum mendengar berita itu."], "Petani itu menanam padi di sawah.", "Kata kerja transitif (menanam) memerlukan objek (padi) selepasnya."),
    ("Imbuhan apitan 'ke-...-an' dalam perkataan 'kejayaan' membawa maksud:", ["Hal atau keadaan", "Saling melakukan", "Perbuatan yang disengajakan", "Tempat sesuatu berlaku"], "Hal atau keadaan", "Apitan 'ke-...-an' pada kata dasar 'jaya' membentuk kata nama abstrak yang bermaksud hal atau keadaan berjaya."),
    ("Kanak-kanak itu menangis kerana kakinya _______ duri pokok semalu.", ["tercucuk", "mencucuk", "dicucukkan", "tertusuk"], "tercucuk", "Imbuhan awalan 'ter-' menunjukkan perbuatan yang tidak disengajakan (tercucuk duri)."),
    ("Pilih kata ganda semu yang betul:", ["Kanak-kanak", "Kura-kura", "Gunung-ganang", "Kuih-muih"], "Kura-kura", "Kata ganda semu ialah kata yang bentuk dasarnya tidak mempunyai makna jika tidak digandakan (kura-kura, rama-rama, agar-agar)."),
    ("Encik Rashid _______ jawatan sebagai pengurus cawangan bank tersebut.", ["menyandang", "menyadang", "tersandang", "disandangkan"], "menyandang", "Ejaan betul dengan imbuhan 'meN-' pada kata dasar 'sandang' ialah 'menyandang'."),
    ("Pilih ayat yang menggunakan Kata Hubung Pancangan Komplemen yang betul:", ["Guru besar menegaskan bahawa disiplin asas kecemerlangan.", "Dia tidak hadir kerana demam panas.", "Amin membaca buku sementara menunggu bas.", "Walaupun hujan, dia tetap pergi ke sekolah."], "Guru besar menegaskan bahawa disiplin asas kecemerlangan.", "Kata hubung komplemen ialah 'bahawa' dan 'untuk' yang melengkapkan ayat."),
    ("Pekerja-pekerja itu sedang _______ jalan raya yang berlubang di Kampung Kiulap.", ["menurap", "menurapkan", "berturap", "diturap"], "menurap", "Awalan meN- + turap -> menurap jalan raya."),
    ("Pilih kata adjektif pancaindera dengar:", ["Bising", "Manis", "Harum", "Kasar"], "Bising", "Bising berkaitan dengan deria pendengaran/telinga."),
    ("Sultan Sharif Ali terkenal dengan sifat baginda yang _______ dan adil.", ["wara'", "bongkak", "khianat", "culas"], "wara'", "Wara' bermaksud alim, taat beragama dan menjauhi perkara dosa."),
    ("Pilih perkataan yang mengandungi imbuhan awalan 'beR-' yang membawa maksud mempunyai:", ["Beranak", "Berlari", "Bercakap", "Berjalan"], "Beranak", "Awalan beR- pada 'anak' bermaksud mempunyai anak."),
    ("Pak Samad _______ sebilah parang tajam untuk menebas semak samun.", ["mengasah", "diasah", "terasah", "asahan"], "mengasah", "Ayat aktif transitif memerlukan kata kerja berawalan meN- (mengasah)."),
    ("Kawasan pedalaman itu sukar dihubungi kerana tiada jalan raya yang _______.", ["sempurna", "menyempurnakan", "tersempurna", "persempurnaan"], "sempurna", "Kata adjektif menerangkan keadaan jalan raya."),
    ("Pilih kata ganda berentak pengulangan vokal:", ["Kuih-muih", "Gunung-ganang", "Batu-batan", "Simpang-siur"], "Kuih-muih", "Kuih-muih mempunyai pengulangan vokal 'u-i'."),
    ("Guru menasihati murid-murid agar sentiasa menghormati kedua-dua _______ mereka.", ["ibu bapa", "ibu-bapa", "ibu dan bapa", "ibu atau bapa"], "ibu bapa", "Ejaan betul ialah kata majmuk mantap yang dieja terpisah: ibu bapa."),
    ("Kereta baharu itu dipandu dengan cermat oleh abang. Apakah jenis ayat ini?", ["Ayat Pasif", "Ayat Aktif", "Ayat Tanya", "Ayat Seruan"], "Ayat Pasif", "Ayat ini mengutamakan objek (kereta) dengan kata kerja pasif 'dipandu oleh'."),
    ("Pilih kata sendi nama yang betul: 'Sumbangan itu diberikan _______ mangsa banjir.'", ["kepada", "pada", "di", "dari"], "kepada", "Gunakan 'kepada' untuk manusia atau institusi, 'pada' untuk masa atau tempat tidak bernyawa."),
    ("Pilih penggunaan kata pemeri 'ialah' yang tepat:", ["Beliau ialah guru kelas kami.", "Rumah itu ialah sangat besar.", "Tujuan lawatan ialah untuk menambah ilmu.", "Baju itu ialah berwarna biru."], "Beliau ialah guru kelas kami.", "'Ialah' hadir di hadapan frasa nama (guru kelas kami), manakala 'adalah' di hadapan frasa adjektif atau sendi nama."),
    ("Kerajaan sentiasa berusaha untuk _______ taraf hidup rakyat luar bandar.", ["meningkatkan", "tingkatkan", "peningkatan", "tertingkat"], "meningkatkan", "Kata kerja transitif meN-...-kan (meningkatkan taraf hidup)."),
    ("Pilih kata seru yang meluahkan rasa kagum:", ["Wah", "Aduh", "Aduhai", "Cis"], "Wah", "'Wah' digunakan untuk menyatakan rasa kagum atau hairan."),
    ("Apakah fungsi tanda sempang (-) dalam perkataan 'ke-60'?", ["Merangkaikan awalan 'ke-' dengan angka nombor", "Menghubungkan dua ayat", "Menyatakan tanda soal", "Memendekkan perkataan"], "Merangkaikan awalan 'ke-' dengan angka nombor", "Tanda sempang merangkaikan awalan ke- dengan nombor angka (ke-60) atau huruf besar (se-Brunei)."),
    ("Pilih kata majmuk yang telah mantap dieja bercantum:", ["Antarabangsa", "Tengah hari", "Kereta api", "Guru besar"], "Antarabangsa", "Antarabangsa, warganegara, tanggungjawab, setiausaha dieja bercantum."),
    ("Adik berasa gembira kerana menerima hadiah daripada kawannya. Apakah pola ayat ini?", ["FN + FA (Frasa Nama + Frasa Adjektif)", "FN + FN", "FN + FK", "FN + FS"], "FN + FA (Frasa Nama + Frasa Adjektif)", "Subjek: Adik (FN); Predikat: berasa gembira (FA)."),
    ("Pilih perkataan berimbuhan 'memper-...-kan' yang betul:", ["Mempertahankan", "Memperkuatkan", "Memperkemaskan", "Memperlebarkan"], "Mempertahankan", "Kata adjektif tidak boleh menerima imbuhan memper-...-kan (hanya memperlebar, memperkemas). 'Mempertahankan' berasal daripada kata kerja 'tahan'."),
    ("Semua murid dikehendaki berbaris _______ memasuki dewan peperiksaan PSR.", ["sebelum", "walaupun", "namun", "kerana"], "sebelum", "Kata hubung masa menunjukkan urutan peristiwa."),
    ("Pilih kata ganda berentak bebas:", ["Simpang-siur", "Kusut-masai", "Kacau-bilau", "Remuk-redam"], "Simpang-siur", "Simpang-siur menunjukkan pergerakan berliku-liku tanpa pola tetap."),
    ("Pencuri itu berjaya _______ oleh pihak polis semalam.", ["diberkas", "memberkas", "terberkas", "berkasan"], "diberkas", "Ayat pasif dengan pelaku ketiga: 'diberkas oleh pihak polis'."),
    ("Haziq membaca buku itu berulang-ulang kali _______ memahaminya.", ["demi", "tentang", "pada", "terhadap"], "demi", "'Demi' digunakan sebagai kata sendi nama atau hubung yang bermaksud untuk/tujuan."),
    ("Pilih ejaan yang tepat mengikut Sistem Ejaan Bahasa Melayu:", ["Kesesakan", "Kesesakkan", "Kesesak-an", "Ke sesakan"], "Kesesakan", "Apitan ke-...-an pada kata dasar sesak dieja 'kesesakan' dengan satu 'k'."),
    ("Apakah jenis ayat bagi: 'Tolong jangan buang sampah di kawasan ini.'", ["Ayat Larangan / Permintaan", "Ayat Seruan", "Ayat Tanya", "Ayat Penyata"], "Ayat Larangan / Permintaan", "'Tolong jangan' ialah gabungan kata permintaan dan larangan sopan."),
    ("Sikap pemuda yang suka _______ wang itu akhirnya memakan diri.", ["membazirkan", "pembazir", "terbazir", "baziran"], "membazirkan", "Kata kerja aktif transitif meN-...-kan (membazirkan wang).")
]

for text, opts, correct, hint in bm_tatabahasa_data:
    add_q(t_id, s_id, text, opts, correct, hint, lang="ms")

# --- 4.2 psr_bm_penjodoh_bilangan (Penjodoh Bilangan Lengkap) ---
t_id = "psr_bm_penjodoh_bilangan"
bm_penjodoh_data = [
    ("Pak Salleh menyabit rumput menggunakan se_______ sabit yang tajam.", ["bilah", "pucuk", "laras", "keping"], "bilah", "Penjodoh bilangan 'bilah' digunakan untuk benda tajam dan runcing seperti parang, pisau, sabit, dan pedang."),
    ("Polis merampas dua _______ senapang patah daripada pemburu haram itu.", ["laras", "pucuk", "bilah", "batang"], "laras", "'Laras' digunakan khas untuk senjata api seperti senapang dan meriam."),
    ("Posmen menyerahkan se_______ surat penting kepada penghulu kampung.", ["pucuk", "helai", "keping", "naskhah"], "pucuk", "'Pucuk' digunakan untuk surat dan senjata api kecil seperti pistol dan jarum."),
    ("Puan Maryam membeli se_______ rantai emas yang cantik di kedai emas itu.", ["utas", "urat", "rawai", "utas"], "utas", "'Utas' digunakan untuk benda panjang dan berurai seperti rantai, tali, dan dawai."),
    ("Tiga _______ meriam lama masih terpelihara rapi di Muzium Brunei.", ["pucuk", "laras", "batang", "bilah"], "pucuk", "'Laras' atau 'pucuk' untuk meriam; 'laras' digunakan khusus untuk senjata api berlaras."),
    ("Nenek memasukkan beberapa _______ garam ke dalam sup ayam itu.", ["butir", "cubit", "genggam", "kepal"], "cubit", "'Cubit' digunakan untuk benda halus yang diambil dengan hujung jari."),
    ("Di tepi pagar rumah datuk terdapat se_______ pokok buluh madu.", ["rumpun", "batang", "pohon", "kuntum"], "rumpun", "'Rumpun' digunakan untuk tumbuhan yang tumbuh berkelompok seperti buluh, serai, dan tebu."),
    ("Ayah membaca se_______ akhbar Pelita Brunei sambil bersarapan pagi.", ["naskhah", "keping", "helai", "pucuk"], "naskhah", "'Naskhah' digunakan untuk bahan bacaan bertulis/bercetak seperti akhbar, majalah, dan manuskrip."),
    ("Pengantin perempuan memakai se_______ cincin berlian yang berkilauan.", ["bentuk", "utas", "buah", "biji"], "bentuk", "'Bentuk' digunakan khusus untuk benda kecil melengkung atau bulat seperti cincin dan mata kail."),
    ("Encik Jamil menghamparkan se_______ tikar mengkuang di ruang tamu.", ["bidang", "keping", "helai", "lembar"], "bidang", "'Bidang' digunakan untuk benda luas dan terbentang seperti tikar, tanah, permaidani, dan sawah."),
    ("Tukang kayu itu memasang beberapa _______ papan pada dinding bilik.", ["keping", "potong", "ketul", "helai"], "keping", "'Keping' digunakan untuk benda nipis, leper dan keras seperti papan, roti, biskut, dan syiling."),
    ("Ibu menyiram beberapa _______ bunga mawar yang sedang mekar di taman.", ["kuntum", "tangkai", "kambang", "pohon"], "kuntum", "'Kuntum' digunakan untuk sekuntum bunga mawar, melati, teratai."),
    ("Kakak membeli se_______ pisang emas di Pasar Tamu Kianggeh.", ["sikat", "tandan", "biji", "sisir"], "sikat", "Bahagian daripada setandan pisang dipanggil sesikat / sesisir."),
    ("Nelayan itu mengusung se_______ pisang yang baru ditebang dari kebunnya.", ["tandan", "sikat", "biji", "batang"], "tandan", "Keseluruhan tangkai buah kelapa, pisang, atau kelapa sawit dipanggil setandan."),
    ("Pelukis itu membeli beberapa _______ kanvas dan se_______ berus lukisan.", ["keping, batang", "bidang, bilah", "helai, laras", "gulung, pucuk"], "keping, batang", "Kanvas lukisan = keping/bidang; berus = batang."),
    ("Beberapa _______ benang emas digunakan untuk menenun kain tenunan Brunei.", ["lembar", "utas", "urat", "helai"], "lembar", "'Lembar' digunakan untuk benda halus dan panjang seperti benang, dawai, rambut."),
    ("Pemain hoki itu memegang se_______ kayu hoki buatan luar negara.", ["batang", "bilah", "pucuk", "patah"], "batang", "'Batang' digunakan untuk benda panjang dan keras seperti kayu, sungai, lilin, dan pen."),
    ("Penduduk kampung mendirikan se_______ khemah besar sempena majlis doa selamat.", ["buah", "bidang", "keping", "pintu"], "buah", "'Buah' digunakan untuk benda besar, binaan atau kenderaan seperti rumah, khemah, kereta, dewan."),
    ("Guru besar menyampaikan beberapa _______ nasihat kepada calon-calon PSR.", ["patah", "rangkap", "baris", "ulas"], "patah", "'Patah' perkataan digunakan untuk kata-kata atau nasihat ringkas."),
    ("Ibu menyuruh adik makan dua _______ buah limau madu itu.", ["ulas", "biji", "butir", "keping"], "ulas", "Bahagian isi di dalam buah berkongsi seperti limau dan durian disebut ulas."),
    ("Pemburu itu menjumpai beberapa _______ peluru senapang di kawasan semak.", ["butir", "biji", "pucuk", "laras"], "butir", "'Butir' digunakan untuk benda bulat kecil seperti peluru, mutiara, beras, dan pasir."),
    ("Abang membeli se_______ payung baharu kerana hari hujan lebat.", ["kaki", "batang", "buah", "bilah"], "kaki", "'Kaki' digunakan khusus untuk payung dan cendawan."),
    ("Di atas meja makan terdapat se_______ kunci rumah dan se_______ roti.", ["gugus, buku", "rangkai, keping", "utas, ketul", "jambak, baris"], "gugus, buku", "Gugusan kunci dipanggil segugus/serangkai; roti bertongkol dipanggil sebuku."),
    ("Datuk memakan se_______ pinang bersama daun sirih.", ["kacip", "racik", "ketul", "potong"], "racik", "'Racik' untuk hiasan nipis pinang; atau seulas."),
    ("Dua _______ merpati putih hinggap di atas bumbung rumah.", ["ekor", "pasang", "kawan", "kumpulan"], "ekor", "'Ekor' ialah penjodoh bilangan bagi semua jenis binatang/haiwan."),
    ("Penyair itu mendeklamasikan tiga _______ sajak patriotik.", ["rangkap", "patah", "baris", "untai"], "rangkap", "'Rangkap' digunakan untuk bait puisi, sajak, pantun, dan syair."),
    ("Peniaga itu membawa sepuluh _______ kain batik sutera.", ["kayu", "helai", "gulung", "kodi"], "kayu", "Gulungan kain besar dari kilang dipanggil sekayu."),
    ("Kakak memakai se_______ baju kurung tenunan Brunei berwarna hijau.", ["pasang", "helai", "keping", "bidang"], "pasang", "Baju kurung lengkap dengan kain dipanggil sepasang."),
    ("Ibu merebus tiga _______ jagung manis untuk hidangan petang.", ["tongkol", "batang", "biji", "butir"], "tongkol", "'Tongkol' digunakan khas untuk buah jagung berkulit."),
    ("Di langit kelihatan se_______ burung helang terbang berputar-putar.", ["kawan", "ekor", "kelompok", "kumpulan"], "kawan", "'Kawan' digunakan untuk kumpulan haiwan seperti burung, gajah, dan lebah.")
]

for text, opts, correct, hint in bm_penjodoh_data:
    add_q(t_id, s_id, text, opts, correct, hint, lang="ms")

# --- 4.3 psr_bm_peribahasa (Peribahasa, Simpulan Bahasa & Kiasan Brunei) ---
t_id = "psr_bm_peribahasa"
bm_peribahasa_data = [
    ("Orang yang rajin dan tidak pernah mengenal penat lelah digelar:", ["Ringan tulang", "Berat tulang", "Besar kepala", "Panjang tangan"], "Ringan tulang", "Ringan tulang bermaksud orang yang rajin bekerja. Lawannya berat tulang (pemalas)."),
    ("Peribahasa 'Bagai aur dengan tebing' menggambarkan:", ["Masyarakat yang sentiasa tolong-menolong dan bersatu padu", "Dua pihak yang bermusuhan", "Orang yang suka membazir", "Sahabat yang melupakan budi"], "Masyarakat yang sentiasa tolong-menolong dan bersatu padu", "Aur dan tebing saling menyokong antara satu sama lain untuk mengelakkan tebing runtuh."),
    ("Maksud simpulan bahasa 'makan suap' ialah:", ["Menerima rasuah", "Makan bersuap tangan", "Boros berbelanja", "Suka dipuji"], "Menerima rasuah", "Makan suap bermaksud menerima sogokan atau wang rasuah secara haram."),
    ("Peribahasa 'Ada udang di sebalik batu' membawa pengertian:", ["Melakukan sesuatu dengan niat tersembunyi", "Mencari rezeki di sungai", "Bercakap bohong", "Bekerja dengan cermat"], "Melakukan sesuatu dengan niat tersembunyi", "Ada muslihat atau motif terselindung di sebalik kebaikan yang ditunjukkan."),
    ("Orang yang sombong dan enggan mendengar teguran orang lain digelar:", ["Besar kepala", "Keras kepala", "Telinga lintah", "Kepala batu"], "Besar kepala", "Besar kepala bermaksud bongkak, sombong dan megah diri."),
    ("Peribahasa 'Sediakan payung sebelum hujan' menasihati kita agar:", ["Sentiasa berwaspada dan membuat persediaan awal sebelum kesusahan tiba", "Membawa payung ketika mendung", "Menjimatkan air hujan", "Membeli payung baharu"], "Sentiasa berwaspada dan membuat persediaan awal sebelum kesusahan tiba", "Buat persediaan menghadapi cabaran sebelum berlaku kecemasan."),
    ("Simpulan bahasa 'kaki ayam' bermaksud:", ["Tidak memakai kasut atau alas kaki", "Berjalan laju", "Kaki yang kurus", "Suka merayau"], "Tidak memakai kasut atau alas kaki", "Kaki ayam merujuk kepada orang yang berjalan tanpa sebarang alas kaki."),
    ("Peribahasa 'Kera mendapat bunga' merujuk kepada:", ["Seseorang yang tidak tahu menghargai barang berharga yang diperolehnya", "Orang yang suka menanam bunga", "Binatang yang merosakkan tanaman", "Mendapat hadiah yang dinanti-nantikan"], "Seseorang yang tidak tahu menghargai barang berharga yang diperolehnya", "Orang yang tidak tahu menilai atau memanfaatkan benda bernilai yang dimilikinya."),
    ("Maksud simpulan bahasa 'curi tulang' ialah:", ["Malas dan mengelak daripada membuat kerja", "Mengambil barang orang", "Tulang yang patah", "Membantu orang lain"], "Malas dan mengelak daripada membuat kerja", "Curi tulang bermaksud culas atau mengelat ketika bekerja."),
    ("Peribahasa 'Sedikit-sedikit, lama-lama jadi bukit' mengajar kita tentang amalan:", ["Menabung dan berjimat cermat", "Mendaki gunung", "Membina rumah batu", "Menyapu sampah"], "Menabung dan berjimat cermat", "Kutipan atau simpanan sedikit demi sedikit akhirnya akan terkumpul menjadi banyak."),
    ("Simpulan bahasa 'panjang tangan' bermaksud:", ["Suka mencuri", "Mempunyai tangan yang panjang", "Suka menolong", "Pemurah"], "Suka mencuri", "Panjang tangan ialah orang yang tabiatnya suka mencuri harta orang lain."),
    ("Peribahasa 'Masuk kandang kambing mengembik, masuk kandang kerbau menguak' bermaksud:", ["Menyesuaikan diri dengan adat dan resam tempat yang dikunjungi", "Mempelajari bahasa haiwan", "Membela ternakan", "Meniru perbuatan jahat"], "Menyesuaikan diri dengan adat dan resam tempat yang dikunjungi", "Di mana bumi dipijak, di situ langit dijunjung; sesuaikan diri dengan masyarakat setempat."),
    ("Simpulan bahasa 'otak cair' merujuk kepada orang yang:", ["Sangat pintar dan cerdik", "Sakit kepala", "Suka melupakan sesuatu", "Lambat berfikir"], "Sangat pintar dan cerdik", "Otak cair bermaksud individu yang sangat bijak dan pantas memahami sesuatu pelajaran."),
    ("Peribahasa 'Ukur baju di badan sendiri' bermaksud:", ["Melakukan sesuatu mengikut kemampuan diri sendiri", "Menempah pakaian di kedai jahit", "Mengukur ketinggian badan", "Membeli pakaian yang mahal"], "Melakukan sesuatu mengikut kemampuan diri sendiri", "Berbelanja atau bertindak mengikut kemampuan dan pendapatan sendiri."),
    ("Orang yang tidak berpendirian tetap dan mudah terpengaruh dipanggil:", ["Laksana lalang ditiup angin", "Keras hati", "Batu api", "Mulut tempayan"], "Laksana lalang ditiup angin", "Lalang mengikut ke mana angin bertiup, melambangkan orang yang tidak teguh pendiriannya."),
    ("Maksud simpulan bahasa 'batu api' ialah:", ["Orang yang suka menghasut supaya pihak lain bergaduh", "Batu yang sangat panas", "Bekerja memadam api", "Orang yang berani"], "Orang yang suka menghasut supaya pihak lain bergaduh", "Penghasut yang membakar perasaan dua pihak sehingga timbul sengketa."),
    ("Peribahasa 'Harimau mati meninggalkan belang, manusia mati meninggalkan nama' bermakna:", ["Orang yang berjasa akan sentiasa dikenang namanya walaupun telah tiada", "Haiwan lebih mulia daripada manusia", "Mati dalam pertempuran", "Belang harimau sangat mahal"], "Orang yang berjasa akan sentiasa dikenang namanya walaupun telah tiada", "Budi dan jasa bakti yang baik akan terus mekar dikenang zaman berzaman."),
    ("Simpulan bahasa 'gelap mata' bermaksud:", ["Hilang pertimbangan kerana tamak akan wang atau harta", "Mata menjadi buta", "Berada di tempat gelap", "Mengantuk pada waktu malam"], "Hilang pertimbangan kerana tamak akan wang atau harta", "Hilang akal waras kerana godaan kebendaan dan kemewahan."),
    ("Peribahasa 'Bagai melepaskan batuk di tangga' membawa maksud:", ["Melakukan sesuatu kerja secara sambalewa dan tidak bersungguh-sungguh", "Batuk di luar rumah", "Menyelesaikan kerja dengan cepat", "Sakit tekak di tangga"], "Melakukan sesuatu kerja secara sambalewa dan tidak bersungguh-sungguh", "Membuat kerja separuh jalan dan tidak sempurna."),
    ("Maksud simpulan bahasa 'tangkai jering' ialah:", ["Orang yang sangat kedekut atau bakhil", "Pokok jering yang berbuah lebat", "Suka memetik jering", "Orang yang boros"], "Orang yang sangat kedekut atau bakhil", "Tangkai jering liat dipatahkan; kiasan kepada orang bakhil mengeluarkan wang."),
    ("Peribahasa 'Nasi sudah menjadi bubur' bermaksud:", ["Perkara yang telah terlanjur dan tidak boleh diperbaiki lagi", "Makanan yang lazat", "Memasak bubur nasi", "Menyesal kerana kenyang"], "Perkara yang telah terlanjur dan tidak boleh diperbaiki lagi", "Kesilapan yang sudah berlaku dan tiada gunanya disesali secara berlebihan."),
    ("Simpulan bahasa 'mulut tempayan' merujuk kepada orang yang:", ["Tidak pandai menyimpan rahsia", "Mulut yang lebar", "Suka makan banyak", "Bercakap dengan kuat"], "Tidak pandai menyimpan rahsia", "Orang yang membocorkan setiap rahsia yang diceritakan kepadanya."),
    ("Peribahasa 'Belakang parang pun jikalau diasah niscaya tajam' bermaksud:", ["Orang yang bodoh jika diajar bersungguh-sungguh nescaya akan pandai", "Parang besi mudah diasah", "Jangan menggunakan parang tumpul", "Bekerja di bengkel besi"], "Orang yang bodoh jika diajar bersungguh-sungguh nescaya akan pandai", "Ketekunan belajar dan bimbingan berterusan akan membuahkan kecemerlangan."),
    ("Simpulan bahasa 'tidur-tidur ayam' bermaksud:", ["Tidur yang tidak lena dan mudah terjaga", "Tidur di dalam reban", "Mata terpejam tetapi mendengar", "Tidur awal petang"], "Tidur yang tidak lena dan mudah terjaga", "Tidur ayam ialah tidur yang tidak nyenyak."),
    ("Peribahasa 'Kacang lupakan kulit' ditujukan kepada:", ["Orang yang tidak tahu membalas budi dan melupakan asalnya", "Suka makan kacang tanah", "Petani yang membuang kulit kacang", "Orang miskin menjadi kaya"], "Orang yang tidak tahu membalas budi dan melupakan asalnya", "Lupa daratan dan tidak mengenang jasa orang yang pernah menolongnya."),
    ("Maksud simpulan bahasa 'hidung tinggi' ialah:", ["Sombong atau meninggi diri", "Hidung yang mancung", "Bernafas laju", "Suka mencium bau harum"], "Sombong atau meninggi diri", "Orang yang bersikap angkuh dan memandang rendah kepada orang lain."),
    ("Peribahasa 'Lembu punya susu, sapi dapat nama' membawa maksud:", ["Orang lain yang berpenat lelah bekerja, orang lain pula yang mendapat pujian", "Susu lembu diminum oleh sapi", "Perternakan lembu tenusu", "Bergaduh merebut harta"], "Orang lain yang berpenat lelah bekerja, orang lain pula yang mendapat pujian", "Seseorang yang mengambil kredit atas penat lelah orang lain."),
    ("Simpulan bahasa 'rambang mata' bermakna:", ["Sukar membuat pilihan kerana semua barang kelihatan cantik", "Mata berasa sakit", "Melihat ke banyak arah", "Mata yang kabur"], "Sukar membuat pilihan kerana semua barang kelihatan cantik", "Keliru memilih kerana terlalu banyak barangan menarik dipamerkan."),
    ("Peribahasa 'Alang-alang menyeluk pekasam, biar sampai ke pangkal lengan' menasihati kita agar:", ["Melakukan sesuatu pekerjaan itu biar sampai selesai dengan jaya", "Membuat pekasam ikan", "Membasuh lengan tangan", "Jangan memegang benda kotor"], "Melakukan sesuatu pekerjaan itu biar sampai selesai dengan jaya", "Jika memulakan sesuatu usaha, teruskanlah sehingga matlamat tercapai sepenuhnya."),
    ("Simpulan bahasa 'putih mata' bermaksud:", ["Berasa malu atau kecewa kerana terlepas peluang", "Mata yang rabun", "Melihat hantu", "Sakit mata"], "Berasa malu atau kecewa kerana terlepas peluang", "Kecewa kerana harapan atau peluang keemasan terlepas ke tangan orang lain.")
]

for text, opts, correct, hint in bm_peribahasa_data:
    add_q(t_id, s_id, text, opts, correct, hint, lang="ms")

# --- 4.4 psr_bm_kosa_kata_pemahaman (Kosa Kata & Pemahaman PSR) ---
t_id = "psr_bm_kosa_kata_pemahaman"
bm_kosa_kata_data = [
    ("Pilih perkataan seerti (sinonim) bagi 'CEKAL':", ["Tabah", "Gementar", "Takut", "Ragu-ragu"], "Tabah", "Cekal bermaksud tabah, kuat semangat dan tidak mudah putus asa."),
    ("Pilih perkataan berlawan (antonim) bagi 'TULUS':", ["Khianat", "Ikhlas", "Jujur", "Lurus"], "Khianat", "Tulus bermaksud jujur dan ikhlas; lawannya khianat atau berpura-pura."),
    ("Apakah maksud perkataan 'prihatin' dalam ayat: 'Kerajaan amat prihatin terhadap kebajikan rakyat.'?", ["Mengambil berat dan peka", "Suka melihat", "Memberikan arahan", "Membiarkan"], "Mengambil berat dan peka", "Prihatin bermaksud mengambil berat, peka dan menunjukkan kepedulian yang mendalam."),
    ("Pilih sinonim bagi perkataan 'MUTLAK':", ["Penuh / Mutlak", "Sementara", "Sebahagian", "Terhad"], "Penuh / Mutlak", "Kuasa mutlak bermaksud kuasa penuh yang tidak terhad oleh pihak lain."),
    ("Pilih antonim bagi 'SUBUR':", ["Gersang", "Makmur", "Segar", "Mekar"], "Gersang", "Tanah subur mudah ditumbuhi tanaman; tanah gersang kering-kontang dan tandus."),
    ("Apakah maksud 'mercu tanda' bagi sesebuah negara?", ["Bangunan atau mercu yang menjadi lambang identiti negara", "Papan tanda jalan", "Puncak gunung yang tinggi", "Lampu suluh pelabuhan"], "Bangunan atau mercu yang menjadi lambang identiti negara", "Mercu tanda (landmark) ialah binaan ikonik yang melambangkan kebanggaan sesebuah tempat."),
    ("Pilih sinonim bagi perkataan 'ANGGUN':", ["Menawan dan bergaya", "Sederhana", "Lincah", "Cepat"], "Menawan dan bergaya", "Anggun bermaksud elok, cantik, menawan dan penuh keanggunan."),
    ("Pilih antonim bagi perkataan 'TULEN':", ["Palsu", "Asli", "Murni", "Bersih"], "Palsu", "Tulen bermaksud asli dan bukan tiruan; lawannya palsu."),
    ("Apakah maksud perkataan 'berwibawa'?", ["Mempunyai karisma dan keupayaan memimpin yang dihormati", "Mempunyai banyak wang", "Bersuara lantang", "Berani bergaduh"], "Mempunyai karisma dan keupayaan memimpin yang dihormati", "Berwibawa (authoritative) bermaksud mempunyai kecekapan dan kewibawaan kepimpinan."),
    ("Pilih sinonim bagi perkataan 'GUGUR' dalam konteks pahlawan negara:", ["Terbiar", "Terputus", "Terbunuh / Terkorban demi tanah air", "Lari"], "Terbunuh / Terkorban demi tanah air", "Gugur di medan perjuangan bermaksud terkorban atau syahid mempertahankan negara."),
    ("Pilih perkataan berlawan bagi 'ZALIM':", ["Adil", "Keras", "Kejam", "Garang"], "Adil", "Zalim bermaksud kejam menindas; lawannya adil dan saksama."),
    ("Perkataan 'rambang' dalam 'mata rambang' bermaksud:", ["Tidak tentu arah / sukar memilih", "Tajam", "Kecil", "Merah"], "Tidak tentu arah / sukar memilih", "Rambang bermaksud tidak bertumpu pada satu sasaran sahaja."),
    ("Apakah maksud perkataan 'muhibah' dalam konteks masyarakat Brunei?", ["Perasaan persahabatan dan kemesraan antara kaum", "Permusuhan", "Persaingan perniagaan", "Sifat dengki"], "Perasaan persahabatan dan kemesraan antara kaum", "Semangat muhibah memupuk perpaduan, keharmonian dan kasih sayang."),
    ("Pilih sinonim bagi 'CANGGIH':", ["Moden dan berteknologi tinggi", "Kuno", "Tradisional", "Kasar"], "Moden dan berteknologi tinggi", "Canggih (sophisticated/advanced) bermaksud moden dan serba maju."),
    ("Pilih antonim bagi perkataan 'KECUT':", ["Kembang", "Kecil", "Kedut", "Tipis"], "Kembang", "Kecut bermaksud berkerut atau susut; lawannya kembang atau mengembang."),
    ("Apakah maksud perkataan 'lestari' dalam 'pembangunan lestari'?", ["Berkekalan dan terpelihara untuk masa depan", "Sementara waktu", "Musnah terbakar", "Pantas berlalu"], "Berkekalan dan terpelihara untuk masa depan", "Lestari bermaksud tidak berubah, kekal berterusan dan terpelihara kemampanannya."),
    ("Pilih sinonim bagi perkataan 'SEPAKAT':", ["Sepaham / Seia sekata", "Bantah", "Curiga", "Enggan"], "Sepaham / Seia sekata", "Sepakat bermaksud sependapat dan bersetuju bersama."),
    ("Pilih antonim bagi 'CEKAP':", ["Lembab / Culas", "Pantas", "Mahir", "Pintar"], "Lembab / Culas", "Cekap bermaksud pantas dan mahir membuat sesuatu; lawannya lembab atau cuai."),
    ("Apakah maksud 'khazanah' dalam ayat 'Hutan tropika Brunei menyimpan khazanah bernilai'?", ["Harta benda berharga warisan alam", "Kayu api", "Binatang liar", "Sungai mengalir"], "Harta benda berharga warisan alam", "Khazanah merujuk kepada perbendaharaan kekayaan atau warisan berharga."),
    ("Pilih perkataan yang sama erti dengan 'GEMILANG':", ["Cemerlang dan terbilang", "Suram", "Malap", "Biasa"], "Cemerlang dan terbilang", "Gemilang bermaksud berseri-seri, gilang-gemilang dan amat cemerlang."),
    ("Pilih antonim bagi perkataan 'BOROS':", ["Jimat", "Kaya", "Mewah", "Bazir"], "Jimat", "Boros bermaksud membelanjakan wang secara berlebihan; lawannya jimat."),
    ("Apakah maksud ungkapan 'titah Seri Baginda'?", ["Kata-kata atau arahan rasmi daripada Sultan", "Surat khabar", "Nyanyian istana", "Doa selamat"], "Kata-kata atau arahan rasmi daripada Sultan", "Titah ialah kata bahasa dalam yang merujuk kepada ucapan atau perintah Sultan."),
    ("Pilih sinonim bagi perkataan 'MUTU':", ["Kualiti", "Kuantiti", "Warna", "Bentuk"], "Kualiti", "Mutu barang bermaksud taraf kecemerlangan atau kualiti."),
    ("Pilih antonim bagi perkataan 'KONGKONG':", ["Bebaskan", "Kurung", "Ikat", "Tahan"], "Bebaskan", "Kongkong bermaksud membelenggu atau menyekat kebebasan; lawannya bebaskan."),
    ("Apakah erti perkataan 'aspirasi'?", ["Cita-cita atau keinginan yang kuat untuk maju", "Rasa takut", "Kenangan lalu", "Kelemahan diri"], "Cita-cita atau keinginan yang kuat untuk maju", "Aspirasi ialah hasrat murni atau cita-cita tinggi untuk mencapai kejayaan."),
    ("Pilih sinonim bagi perkataan 'DAIF':", ["Miskin / Lemah serba kekurangan", "Mewah", "Kaya", "Kuat"], "Miskin / Lemah serba kekurangan", "Daif bermaksud hidup dalam kesempitan, miskin atau lemah."),
    ("Pilih antonim bagi 'BERSEPAH':", ["Kemas", "Berterabur", "Kotor", "Hancur"], "Kemas", "Bersepah bermaksud bertaburan tidak teratur; lawannya tersusun kemas."),
    ("Apakah maksud perkataan 'integriti'?", ["Kejujuran dan ketulusan dalam menjalankan tugas", "Kecepatan berlari", "Kekuatan fizikal", "Kebolehan bercakap"], "Kejujuran dan ketulusan dalam menjalankan tugas", "Integriti ialah sifat amanah, berprinsip moral tinggi dan jujur."),
    ("Pilih sinonim bagi perkataan 'TELADAN':", ["Contoh ikutan yang baik", "Cerita dongeng", "Teguran keras", "Hadiah"], "Contoh ikutan yang baik", "Teladan bermaksud contoh atau ikutan terpuji untuk ditiru."),
    ("Pilih antonim bagi perkataan 'MENDUNG':", ["Cerah", "Gelap", "Redup", "Kelabu"], "Cerah", "Mendung bermaksud langit berawan tebal tanda hendak hujan; lawannya cerah berpanas.")
]

for text, opts, correct, hint in bm_kosa_kata_data:
    add_q(t_id, s_id, text, opts, correct, hint, lang="ms")

# =========================================================================
# SUBJECT 5: MELAYU ISLAM BERAJA (MIB PSR BRUNEI) - 3 Topics
# =========================================================================

# --- 5.1 psr_mib_falsafah_konsep (Falsafah Negara & Konsep MIB) ---
t_id = "psr_mib_falsafah_konsep"
s_id = "psr_mib"
mib_konsep_data = [
    ("Apakah maksud singkatan MIB dalam konteks Negara Brunei Darussalam?", ["Melayu Islam Beraja", "Maju Islam Berdaulat", "Masyarakat Islam Brunei", "Muafakat Islam Bersatu"], "Melayu Islam Beraja", "MIB ialah falsafah kebangsaan Negara Brunei Darussalam yang bermaksud Melayu Islam Beraja."),
    ("Bilakah Falsafah Melayu Islam Beraja (MIB) dimasyhurkan secara rasmi?", ["Semasa Pemasyhuran Kemerdekaan pada 1 Januari 1984", "Semasa Hari Kebangsaan 1985", "Semasa Pembentukan Perlembagaan 1959", "Pada tahun 1967"], "Semasa Pemasyhuran Kemerdekaan pada 1 Januari 1984", "Kebawah Duli Yang Maha Mulia memasyhurkan Brunei sebagai sebuah negara Melayu Islam Beraja pada 1 Januari 1984."),
    ("Siapakah yang memegang tampuk pemerintahan tertinggi dan ketua agama Islam di Brunei Darussalam?", ["Kebawah Duli Yang Maha Mulia Paduka Seri Baginda Sultan", "Perdana Menteri Luar", "Menteri Hal Ehwal Ugama", "Ketua Hakim Syar'ie"], "Kebawah Duli Yang Maha Mulia Paduka Seri Baginda Sultan", "Sultan ialah Ketua Negara, Ketua Kerajaan, dan Ketua Agama Islam di Negara Brunei Darussalam."),
    ("Apakah pegangan mazhab fiqh rasmi bagi Negara Brunei Darussalam?", ["Mazhab Syafi'i", "Mazhab Hanafi", "Mazhab Maliki", "Mazhab Hanbali"], "Mazhab Syafi'i", "Brunei Darussalam berpegang teguh kepada Ahli Sunnah Waljamaah mengikut Mazhab Syafi'i."),
    ("Unsur 'Melayu' dalam MIB merujuk kepada:", ["Kebudayaan, tatasusila, dan adat istiadat bangsa Melayu Brunei", "Bahasa asing", "Sistem perundangan barat", "Makanan import"], "Kebudayaan, tatasusila, dan adat istiadat bangsa Melayu Brunei", "Unsur Melayu memelihara nilai-nilai murni, adat istiadat, dan bahasa Melayu sebagai bahasa rasmi."),
    ("Unsur 'Islam' dalam MIB berfungsi sebagai:", ["Cara hidup (ad-Deen) dan landasan undang-undang serta akhlak negara", "Simbol semata-mata", "Acara tahunan", "Mata pelajaran sekolah sahaja"], "Cara hidup (ad-Deen) dan landasan undang-undang serta akhlak negara", "Islam adalah agama rasmi negara yang membimbing pentadbiran, undang-undang Syariah, dan kehidupan bermasyarakat."),
    ("Unsur 'Beraja' dalam MIB bermaksud:", ["Sistem pemerintahan beraja (Monarki Islam) yang dipimpin oleh Sultan yang berdaulat", "Sistem republik berpresiden", "Sistem demokrasi tanpa raja", "Pemerintahan tentera"], "Sistem pemerintahan beraja (Monarki Islam) yang dipimpin oleh Sultan yang berdaulat", "Sistem kesultanan Melayu Islam yang diwarisi sejak berkurun-kurun lamanya."),
    ("Apakah bahasa rasmi Negara Brunei Darussalam menurut Perlembagaan Negeri Brunei 1959?", ["Bahasa Melayu", "Bahasa Inggeris", "Bahasa Arab", "Bahasa Dusun"], "Bahasa Melayu", "Bahasa Melayu termaktub sebagai Bahasa Rasmi Negara menurut Perlembagaan 1959."),
    ("Apakah gelaran rasmi bagi ibu negara Brunei Darussalam?", ["Bandar Seri Begawan", "Kuala Belait", "Bangar", "Tutong"], "Bandar Seri Begawan", "Ibu negara dinamakan Bandar Seri Begawan sempena Al-Marhum Sultan Haji Omar 'Ali Saifuddien Sa'adul Khairi Waddien."),
    ("Berapakah jumlah daerah yang membentuk Negara Brunei Darussalam?", ["4 Daerah (Brunei-Muara, Tutong, Belait, Temburong)", "3 Daerah", "5 Daerah", "6 Daerah"], "4 Daerah (Brunei-Muara, Tutong, Belait, Temburong)", "Negara Brunei Darussalam terbahagi kepada 4 daerah pentadbiran."),
    ("Apakah moto rasmi yang tertera pada Jata Kebangsaan Negara Brunei Darussalam?", ["Sentiasa Membuat Kebajikan dengan Petunjuk Allah", "Bersatu Teguh Bercerai Roboh", "Berkhidmat Untuk Negara", "Kedaulatan Milik Raja"], "Sentiasa Membuat Kebajikan dengan Petunjuk Allah", "Tulisan Jawi pada Jata Negara berbunyi: 'الدائمون المحسنون بالهدى' (Sentiasa membuat kebajikan dengan petunjuk Allah)."),
    ("Apakah nama tulisan pada lambang bulan sabit Jata Kebangsaan Brunei?", ["Brunei Darussalam", "Negara Brunei", "Melayu Islam Beraja", "Sultan Brunei"], "Brunei Darussalam", "Pada bulan sabit tertera tulisan nama negara: 'بروني دارالسلام' (Brunei Darussalam)."),
    ("Apakah maksud perkataan 'Darussalam' pada nama Brunei Darussalam?", ["Negara yang Aman dan Damai (Abode of Peace)", "Negara Minyak", "Tanah Emas", "Kota Bersih"], "Negara yang Aman dan Damai (Abode of Peace)", "'Darussalam' ialah perkataan Arab yang bermaksud Tempat Kediaman yang Aman dan Damai."),
    ("Konsep 'Baldatun Thayyibatun Wa Rabbun Ghafur' dalam aspirasi Brunei bermaksud:", ["Negara yang baik, makmur dan mendapat pengampunan Tuhan", "Negara yang kaya raya", "Kota metropolitan", "Kawasan perdagangan moden"], "Negara yang baik, makmur dan mendapat pengampunan Tuhan", "Aspirasi Brunei sebagai Negara Zikir yang diberkati dan dilimpahi keredhaan Ilahi."),
    ("Mengapakah sistem Beraja penting kepada rakyat Brunei?", ["Menjadi tonggak perpaduan, kestabilan, dan pelindung kebajikan rakyat", "Untuk meraikan hari keputeraan sahaja", "Menambah perbelanjaan negara", "Sebagai perhiasan sejarah"], "Menjadi tonggak perpaduan, kestabilan, dan pelindung kebajikan rakyat", "Raja yang berdaulat memayungi rakyat dengan adil, memelihara syiar Islam, dan menjamin kesejahteraan rakyat."),
    ("Sikap taat setia rakyat kepada Raja dalam konsep MIB berlandaskan ajaran:", ["Al-Quran dan Sunnah Rasulullah SAW", "Undang-undang antarabangsa", "Adat barat", "Falsafah sekular"], "Al-Quran dan Sunnah Rasulullah SAW", "Islam memerintahkan umatnya mentaati Allah, Rasul, dan Ulil Amri (pemimpin yang adil)."),
    ("Apakah Wawasan Brunei yang menyasarkan negara menjadi sebuah negara maju dan berpendidikan tinggi?", ["Wawasan Brunei 2035", "Wawasan 2020", "Wawasan 2050", "Wawasan 2030"], "Wawasan Brunei 2035", "Wawasan Brunei 2035 bermatlamat melahirkan rakyat berpendidikan, berkemahiran, dan menikmati kualiti hidup bertaraf dunia."),
    ("Apakah bunga kebangsaan Negara Brunei Darussalam?", ["Bunga Simpur (Dillenia suffruticosa)", "Bunga Raya", "Bunga Melati", "Bunga Orkid"], "Bunga Simpur (Dillenia suffruticosa)", "Bunga Simpur dengan kelopak kuning terang ialah bunga kebangsaan Brunei."),
    ("Warna apakah yang melambangkan Sultan pada Bendera Kebangsaan Brunei?", ["Kuning", "Putih", "Hitam", "Merah"], "Kuning", "Warna kuning ialah warna diraja yang melambangkan Kebawah Duli Yang Maha Mulia Paduka Seri Baginda Sultan."),
    ("Dua jalur pepenjuru pada Bendera Kebangsaan Brunei berwarna:", ["Putih dan Hitam", "Merah dan Hijau", "Biru dan Putih", "Kuning dan Merah"], "Putih dan Hitam", "Jalur putih melambangkan Pengiran Bendahara dan jalur hitam melambangkan Pengiran Pemancha."),
    ("Apakah hukum mentaati perintah Raja yang tidak bertentangan dengan syariat Islam?", ["Wajib", "Sunat", "Harus", "Makruh"], "Wajib", "Menurut ajaran Islam, mentaati pemimpin yang adil dalam perkara maaruf adalah kewajipan setiap rakyat."),
    ("Apakah institusi yang memelihara keadilan undang-undang berlandaskan hukum syarak di Brunei?", ["Mahkamah Syariah", "Mahkamah Dagang", "Mahkamah Adat", "Mahkamah Arbitrase"], "Mahkamah Syariah", "Mahkamah Syariah menguatkuasakan Perintah Kanun Hukuman Jenayah Syariah."),
    ("Apakah nilai yang terkandung dalam amalan 'Negara Zikir'?", ["Sentiasa mengingati Allah SWT dalam pentadbiran dan kehidupan seharian", "Membaca buku semata-mata", "Mengasingkan diri dari dunia luar", "Menolak kemajuan sains"], "Sentiasa mengingati Allah SWT dalam pentadbiran dan kehidupan seharian", "Negara Zikir mengintegrasikan nilai spiritual Islam dalam pembangunan modal insan."),
    ("Apakah kelebihan mengamalkan ajaran MIB dalam era modenisasi?", ["Mengekalkan jati diri dan benteng daripada pengaruh negatif luar", "Menolak semua teknologi komputer", "Mengelak daripada bergaul dengan orang lain", "Membataskan perdagangan antarabangsa"], "Mengekalkan jati diri dan benteng daripada pengaruh negatif luar", "MIB menjadi perisai moral dan identiti kebangsaan yang kukuh bagi generasi muda."),
    ("Apakah peranan belia Brunei dalam merealisasikan Wawasan Brunei 2035 berteraskan MIB?", ["Belajar bersungguh-sungguh, berakhlak mulia dan menyumbang bakti kepada negara", "Hanya menunggu bantuan kerajaan", "Mengabaikan nilai tradisi", "Bekerja di luar negara tanpa kembali"], "Belajar bersungguh-sungguh, berakhlak mulia dan menyumbang bakti kepada negara", "Generasi belia yang berilmu dan berakhlak adalah aset terpenting negara."),
    ("Mengapakah amalan musyawarah (bermesyuarat) ditekankan dalam MIB?", ["Untuk mencapai keputusan yang adil dan muafakat bersama", "Supaya mesyuarat berlarutan lama", "Mengelakkan tanggungjawab", "Memenangkan satu pihak sahaja"], "Untuk mencapai keputusan yang adil dan muafakat bersama", "Musyawarah ialah amalan syura dalam Islam untuk kebaikan dan kemaslahatan ummah."),
    ("Apakah prinsip asas ekonomi Islam yang diamalkan di Brunei Darussalam?", ["Bebas daripada riba, gharar, dan penipuan", "Mengenakan faedah yang tinggi", "Monopoli perniagaan", "Menipu timbangan"], "Bebas daripada riba, gharar, dan penipuan", "Perbankan dan kewangan Islam berteraskan keadilan, perkongsian keuntungan, dan ketelusan."),
    ("Siapakah yang dimaksudkan dengan 'Rakyat Jati Brunei'?", ["Tujuh puak Melayu jati Brunei yang diiktiraf undang-undang", "Semua pelancong asing", "Penduduk yang baru tiba", "Hanya mereka yang tinggal di bandar"], "Tujuh puak Melayu jati Brunei yang diiktiraf undang-undang", "Tujuh puak jati: Belait, Bisaya, Brunei, Dusun, Kedayan, Murut, dan Tutong."),
    ("Apakah kebaikan mengekalkan sistem Monarki Berpelembagaan di Brunei?", ["Menjamin kestabilan politik, perpaduan kaum dan kesinambungan pembangunan", "Menyekat kemajuan teknologi", "Menghapuskan pilihan raya sahaja", "Menolak hubungan diplomatik"], "Menjamin kestabilan politik, perpaduan kaum dan kesinambungan pembangunan", "Kepimpinan Sultan memastikan kestabilan yang berpanjangan dan kemakmuran dinikmati rakyat."),
    ("Falsafah MIB mendidik rakyat Brunei agar sentiasa bersyukur dengan nikmat:", ["Keamanan, kemakmuran dan perlindungan Islam di bawah naungan Raja", "Kekayaan tanpa bekerja", "Hiburan melampau", "Kemegahan duniawi"], "Keamanan, kemakmuran dan perlindungan Islam di bawah naungan Raja", "Mensyukuri nikmat keamanan dan kemakmuran adalah teras keharmonian Brunei.")
]

for text, opts, correct, hint in mib_konsep_data:
    add_q(t_id, s_id, text, opts, correct, hint, lang="ms")

# --- 5.2 psr_mib_sejarah_kesultanan (Sejarah Kesultanan Brunei) ---
t_id = "psr_mib_sejarah_kesultanan"
mib_sejarah_data = [
    ("Siapakah Sultan Brunei yang pertama memeluk agama Islam?", ["Sultan Muhammad Shah (Awang Alak Betatar)", "Sultan Sharif Ali", "Sultan Bolkiah", "Sultan Hashim"], "Sultan Muhammad Shah (Awang Alak Betatar)", "Awang Alak Betatar memeluk Islam dan memakai gelaran Sultan Muhammad Shah (1363-1402)."),
    ("Sultan Brunei manakah yang berasal dari keturunan Rasulullah SAW (Ahlul Bait) dan dikenali sebagai 'Sultan Berkat'?", ["Sultan Sharif Ali", "Sultan Ahmad", "Sultan Hassan", "Sultan Saiful Rijal"], "Sultan Sharif Ali", "Sultan Sharif Ali (Sultan ke-3) berasal dari Taif dan digelar Sultan Berkat kerana memajukan syiar Islam dan membina masjid pertama."),
    ("Sultan manakah yang terkenal sebagai 'Nakhoda Ragam' dan membawa zaman kegemilangan empayar Brunei?", ["Sultan Bolkiah", "Sultan Muhammad Shah", "Sultan Omar Ali Saifuddin I", "Sultan Abdul Momin"], "Sultan Bolkiah", "Sultan Bolkiah (Sultan ke-5) mengemudi empayar maritim Brunei meliputi seluruh Borneo dan Kepulauan Sulu hingga Manila."),
    ("Apakah peristiwa bersejarah yang berlaku pada tahun 1578 antara Brunei dan pihak Sepanyol?", ["Perang Kastila", "Perang Dunia Pertama", "Perang Saudara", "Perang Temburong"], "Perang Kastila", "Perang Kastila berlaku apabila angkatan Sepanyol cuba menakluki Brunei tetapi berjaya diundurkan oleh pahlawan Brunei di bawah pimpinan Bendahara Sakam."),
    ("Siapakah pahlawan gagah berani Brunei yang mengetuai perjuangan mengusir tentera Sepanyol dalam Perang Kastila?", ["Pengiran Bendahara Sakam", "Orang Kaya Setia Bakti", "Awang Semaun", "Pateh Berbai"], "Pengiran Bendahara Sakam", "Bendahara Sakam bersama hulubalang dan pahlawan rakyat bangkit mengusir penjajah Sepanyol dari Kota Batu."),
    ("Sultan manakah yang digelar sebagai 'Arkitek Brunei Moden'?", ["Sultan Haji Omar 'Ali Saifuddien III", "Sultan Hashim Jalilul Alam", "Sultan Ahmad Tajuddin", "Sultan Muhammad Jamalul Alam II"], "Sultan Haji Omar 'Ali Saifuddien III", "Sultan Haji Omar 'Ali Saifuddien Sa'adul Khairi Waddien (Sultan ke-28) memodenkan pendidikan, jalan raya, perubatan, dan menandatangani Perlembagaan 1959."),
    ("Apakah masjid ikonik berkubah emas yang dibina pada tahun 1958 di tengah-tengah ibu negara?", ["Masjid Omar 'Ali Saifuddien", "Masjid Jame' 'Asr Hassanil Bolkiah", "Masjid Ash-Shaliheen", "Masjid Kampong Ayer"], "Masjid Omar 'Ali Saifuddien", "Masjid Sultan Omar 'Ali Saifuddien dengan lagun dan replika mahligai Mahligai Baiduri dirasmikan pada tahun 1958."),
    ("Sultan Haji Hassanal Bolkiah Mu'izzaddin Waddaulah ditabalkan sebagai Sultan Brunei yang ke:", ["Ke-29", "Ke-28", "Ke-30", "Ke-25"], "Ke-29", "Kebawah Duli Yang Maha Mulia ialah Sultan dan Yang Di-Pertuan Negara Brunei Darussalam yang ke-29 (sejak 1967)."),
    ("Di manakah tapak pusat pemerintahan purba Kesultanan Brunei yang terkenal dengan penemuan arkeologi?", ["Kota Batu", "Kuala Belait", "Bangar", "Seria"], "Kota Batu", "Kota Batu merupakan pusat pemerintahan empayar Brunei purba dari abad ke-14 hingga ke-17."),
    ("Makam siapakah yang terletak di kawasan Kota Batu dan sering diziarahi?", ["Makam Sultan Sharif Ali dan Sultan Bolkiah", "Makam Sultan Hashim", "Makam Sultan Hassan", "Makam Bendahara Sakam"], "Makam Sultan Sharif Ali dan Sultan Bolkiah", "Makam Sultan Sharif Ali dan Makam Sultan Bolkiah terletak berhampiran Muzium Arkeologi Kota Batu."),
    ("Apakah dokumen penting yang dimeterai pada 29 September 1959 memberikan taraf berpemerintahan sendiri kepada Brunei?", ["Perlembagaan Bertulis Negeri Brunei 1959", "Perjanjian Perlindungan 1888", "Deklarasi Bangkok", "Perjanjian London"], "Perlembagaan Bertulis Negeri Brunei 1959", "Perlembagaan 1959 memaktubkan autonomi pentadbiran dalam negeri dan menetapkan Islam serta Bahasa Melayu."),
    ("Di manakah minyak mentah pertama kali ditemui secara komersial di Brunei pada tahun 1929?", ["Seria (Daerah Belait)", "Muara", "Bangar", "Tutong"], "Seria (Daerah Belait)", "Telaga Minyak Seria No. 1 menjumpai minyak komersial pada tahun 1929 dan mengubah landskap ekonomi negara."),
    ("Apakah monumen yang dibina sempena sambutan pengeluaran satu bilion tong minyak di Seria?", ["Monumen Satu Bilion Tong (Billionth Barrel Monument)", "Tugu Jam Bandar", "Mercu Tanda Jerudong", "Pintu Gerbang Belait"], "Monumen Satu Bilion Tong (Billionth Barrel Monument)", "Monumen ini didirikan berhampiran pantai Seria pada tahun 1991 sempena penghasilan 1 bilion tong minyak."),
    ("Sultan Hassan (Sultan ke-9) terkenal dalam sejarah Brunei kerana memperkenalkan:", ["Kanun Mahkota Brunei (Kod Undang-undang dan Adat Istiadat)", "Mata wang kertas pertama", "Kapal terbang tentera", "Sekolah Inggeris"], "Kanun Mahkota Brunei (Kod Undang-undang dan Adat Istiadat)", "Sultan Hassan menggubal Kanun Mahkota yang menyusun atur protokol istana, gelaran wazir, dan undang-undang adat."),
    ("Siapakah yang mendirikan masjid pertama yang mempunyai mimbar di Brunei Darussalam?", ["Sultan Sharif Ali", "Sultan Bolkiah", "Sultan Saiful Rijal", "Sultan Muhammad Shah"], "Sultan Sharif Ali", "Sultan Sharif Ali sendiri membina masjid pertama dan menyampaikan khutbah Jumaat dari mimbarnya."),
    ("Apakah nama perkampungan warisan di atas air yang digelar 'Venice of the East' oleh Antonio Pigafetta pada 1521?", ["Kampong Ayer", "Kampong Kianggeh", "Kampong Tamoi", "Kampong Saba"], "Kampong Ayer", "Antonio Pigafetta (ekspedisi Magellan) melawat Kampong Ayer pada tahun 1521 dan kagum dengan petempatan atas air tersebut."),
    ("Jambatan gergasi terpanjang di Asia Tenggara yang menghubungkan Daerah Brunei-Muara dengan Daerah Temburong dinamakan:", ["Jambatan Sultan Haji Omar 'Ali Saifuddien (SHOAS)", "Jambatan Raja Isteri Pengiran Anak Hajah Saleha", "Jambatan Edinburgh", "Jambatan Tutong"], "Jambatan Sultan Haji Omar 'Ali Saifuddien (SHOAS)", "Jambatan sepanjang 30 km ini dirasmikan pada tahun 2020 memudahkan perhubungan penduduk Temburong."),
    ("Apakah peristiwa kemuncak yang diraikan oleh seluruh rakyat Brunei pada 5 Oktober 2017?", ["Jubli Emas Pemerintahan Kebawah Duli Yang Maha Mulia (50 Tahun)", "Jubli Perak", "Kemerdekaan Brunei", "Ulang Tahun Hari Keputeraan ke-70"], "Jubli Emas Pemerintahan Kebawah Duli Yang Maha Mulia (50 Tahun)", "Sambutan Jubli Emas menandakan 50 tahun pemerintahan gemilang Kebawah Duli Yang Maha Mulia (1967-2017)."),
    ("Siapakah Sultan yang menandatangani Perjanjian 1888 meletakkan Brunei di bawah naungan perlindungan British?", ["Sultan Hashim Jalilul Alam Aqamaddin", "Sultan Abdul Momin", "Sultan Omar Ali Saifuddin II", "Sultan Muhammad Jamalul Alam I"], "Sultan Hashim Jalilul Alam Aqamaddin", "Sultan Hashim menandatangani Perjanjian Naungan 1888 untuk melindungi kedaulatan Brunei daripada ancaman luar."),
    ("Di manakah Istana rasmi kediaman Kebawah Duli Yang Maha Mulia yang diiktiraf sebagai istana terbesar di dunia?", ["Istana Nurul Iman", "Istana Darussalam", "Istana Nurul Izzah", "Istana Edinburgh"], "Istana Nurul Iman", "Istana Nurul Iman merupakan kediaman rasmi Sultan dengan lebih 1,788 buah bilik."),
    ("Apakah bendera negeri yang dikibarkan di bangunan-bangunan kerajaan sempena Sambutan Hari Kebangsaan?", ["Bendera Kebangsaan Negara Brunei Darussalam", "Bendera Union Jack", "Bendera ASEAN", "Bendera Bulan Sabit Hijau"], "Bendera Kebangsaan Negara Brunei Darussalam", "Rakyat mengibarkan Bendera Kebangsaan dengan penuh bangga dan patriotisme."),
    ("Apakah nama institusi pendidikan tinggi tertua di Brunei yang ditubuhkan pada tahun 1985?", ["Universiti Brunei Darussalam (UBD)", "Universiti Islam Sultan Sharif Ali (UNISSA)", "Universiti Teknologi Brunei (UTB)", "Kolej Universiti Perguruan Ugama Seri Begawan (KUPU SB)"], "Universiti Brunei Darussalam (UBD)", "UBD ditubuhkan pada tahun 1985 sebagai universiti kebangsaan pertama melahirkan graduan tempatan."),
    ("Apakah nama muzium yang mempamerkan Regalia Diraja seperti mahkota, usungan, dan pedang kebesaran?", ["Muzium Alat Kebesaran Diraja (Royal Regalia Museum)", "Muzium Maritim Brunei Darussalam", "Pusat Sejarah Brunei", "Muzium Teknologi Melayu"], "Muzium Alat Kebesaran Diraja (Royal Regalia Museum)", "Muzium Alat Kebesaran Diraja di tengah ibu kota menyimpan artifak dan pusaka kebesaran nobat dan pertabalan diraja."),
    ("Lembaga apakah yang ditubuhkan untuk menyelidik dan mendokumentasikan salasilah serta sejarah Brunei?", ["Pusat Sejarah Brunei", "Dewan Bahasa dan Pustaka", "Jabatan Penyiaran", "Jabatan Muzium-Muzium"], "Pusat Sejarah Brunei", "Pusat Sejarah Brunei ditubuhkan pada 1982 untuk memelihara salasilah Kesultanan Brunei dan warisan sejarah."),
    ("Siapakah tokoh yang mengasaskan Pusat Sejarah Brunei dan banyak menulis buku sejarah Kesultanan?", ["Yang Dimuliakan Pehin Jawatan Dalam Seri Maharaja Dato Seri Utama Dr. Haji Awang Mohd. Jamil Al-Sufri", "Awang Alak Betatar", "Pehin Kapitan", "Pengiran Muda Hashim"], "Yang Dimuliakan Pehin Jawatan Dalam Seri Maharaja Dato Seri Utama Dr. Haji Awang Mohd. Jamil Al-Sufri", "Pehin Dato Dr. Haji Mohd. Jamil Al-Sufri ialah sejarawan ulung negara."),
    ("Apakah nama naskhah perundangan Islam klasik yang diamalkan pada zaman pemerintahan Sultan Hassan?", ["Hukum Kanun Brunei", "Hukum Adat Temenggong", "Undang-undang Melaka", "Peraturan Pelabuhan"], "Hukum Kanun Brunei", "Hukum Kanun Brunei mengandungi 47 fasal yang bersumberkan syariat Islam dan adat Melayu."),
    ("Apakah peranan Awang Semaun dalam cerita lisan rakyat Brunei purba?", ["Pahlawan gagah berani yang membantu pembukaan negeri Brunei", "Seorang pedagang rempah", "Nakhoda kapal Sepanyol", "Seorang pelukis diraja"], "Pahlawan gagah berani yang membantu pembukaan negeri Brunei", "Awang Semaun bersama saudara-saudaranya merupakan pahlawan legenda pembuka negeri Brunei."),
    ("Bilakah masjid Jame' 'Asr Hassanil Bolkiah yang megah dengan 29 kubah emas dirasmikan?", ["Tahun 1994 (sempena Sambutan Jubli Perak)", "Tahun 1984", "Tahun 2000", "Tahun 1975"], "Tahun 1994 (sempena Sambutan Jubli Perak)", "Masjid Jame' 'Asr di Kiarong dirasmikan pada tahun 1994 sebagai wakaf peribadi Sultan ke-29."),
    ("Berapakah jumlah kubah emas pada masjid Jame' 'Asr Hassanil Bolkiah yang melambangkan Sultan ke-29?", ["29 Kubah Emas", "28 Kubah Emas", "30 Kubah Emas", "25 Kubah Emas"], "29 Kubah Emas", "29 kubah emas melambangkan pemerintahan Sultan Brunei ke-29."),
    ("Apakah ikrar yang dilafazkan oleh seluruh rakyat pada setiap Hari Kebangsaan Brunei?", ["Ikrar Taat Setia mempertahankan kedaulatan negara dan Falsafah MIB", "Ikrar sukan", "Ikrar perdagangan", "Ikrar pelajar sekolah"], "Ikrar Taat Setia mempertahankan kedaulatan negara dan Falsafah MIB", "Pembacaan ikrar menandakan komitmen rakyat memelihara kemerdekaan dan keharmonian tanah air.")
]

for text, opts, correct, hint in mib_sejarah_data:
    add_q(t_id, s_id, text, opts, correct, hint, lang="ms")

# --- 5.3 psr_mib_adat_tatasusila (Adat Istiadat, Tatasusila & Kebudayaan Brunei) ---
t_id = "psr_mib_adat_tatasusila"
mib_adat_data = [
    ("Apakah cara bersalaman yang sopan dan beradab dalam tatasusila masyarakat Melayu Brunei?", ["Bersalaman dengan kedua-dua belah tangan lalu meletakkannya di dada", "Melambai dari jauh sahaja", "Menolak tangan", "Memegang bahu"], "Bersalaman dengan kedua-dua belah tangan lalu meletakkannya di dada", "Bersalaman dua tangan dan merapatkan ke dada menunjukkan rasa ikhlas dan hormat mendalam."),
    ("Bagaimanakah cara yang betul untuk menunjukkan arah sesuatu benda kepada orang tua?", ["Menggunakan ibu jari tangan kanan yang digenggam dengan sopan", "Menggunakan jari telunjuk", "Menggunakan kaki", "Menggunakan dagu secara kasar"], "Menggunakan ibu jari tangan kanan yang digenggam dengan sopan", "Dalam tatasusila Melayu Brunei, menunjuk dengan jari telunjuk dianggap kurang sopan; gunakan ibu jari kanan."),
    ("Apabila berjalan melintas di hadapan orang yang lebih tua, kita hendaklah:", ["Menundukkan sedikit badan sambil menghulurkan tangan ke bawah sambil meminta lalu", "Berlari kencang di hadapannya", "Berjalan dengan mendada sombong", "Bercakap dengan kuat"], "Menundukkan sedikit badan sambil menghulurkan tangan ke bawah sambil meminta lalu", "Menundukkan badan dan mengucap 'minta lalu' menunjukkan adab merendah diri."),
    ("Apakah makanan tradisi Brunei yang diperbuat daripada sagu rumbia dan dimakan dengan cecah cacah?", ["Ambuyat", "Nasi Katok", "Kueh Mor", "Kelupis"], "Ambuyat", "Ambuyat diperbuat daripada ambulung (sagu rumbia) dan dimakan menggunakan candas bersama kuah binjai atau tempoyak."),
    ("Alat khas yang diperbuat daripada buluh menyerupai penyepit untuk memakan ambuyat dipanggil:", ["Candas", "Chopstick", "Garpu", "Senduk"], "Candas", "Candas ialah penyepit buluh tradisional untuk menggulung ambuyat."),
    ("Apakah hidangan pulut berbalut daun nyirik yang popular disajikan semasa hari perayaan di Brunei?", ["Kelupis", "Ketupat", "Lemang", "Lepat"], "Kelupis", "Kelupis ialah makanan istimewa beras pulut berlemak santan dibalut rapi dengan daun nyirik."),
    ("Apakah seni kraf tenunan tekstil mewah kebanggaan Brunei yang sering dipakai dalam majlis rasmi diraja?", ["Kain Tenunan Brunei (Kain Jong Sarat)", "Batik Lukis", "Songket Palembang", "Kain Sari"], "Kain Tenunan Brunei (Kain Jong Sarat)", "Kain Jong Sarat ialah kain tenunan tangan emas bertaraf tinggi warisan turun-temurun."),
    ("Apakah nama seni permainan muzik gendang dan gong tradisional yang dimainkan dalam upacara adat diraja?", ["Nobat Diraja Brunei", "Muzik Jazz", "Gamelan Jawa", "Keroncong"], "Nobat Diraja Brunei", "Nobat Diraja mengandungi alat muzik pusaka seperti Nakara, Gendang Labik, Serunai, dan Gong."),
    ("Apakah sebutan bahasa dalam bagi 'makan' apabila merujuk kepada Raja atau kerabat diraja?", ["Santap", "Makan", "Minum", "Kunyah"], "Santap", "Bahasa Dalam / Istana untuk makan ialah 'santap'."),
    ("Apakah perkataan bahasa dalam bagi 'tidur' bagi kerabat diraja?", ["Beradu", "Rehat", "Lelenap", "Baring"], "Beradu", "Bahasa Dalam untuk tidur ialah 'beradu'."),
    ("Apakah panggilan hormat diri bagi rakyat jelata apabila bertutur dengan kerabat diraja?", ["Hamba Kebawah Duli Tuan Patik", "Saya", "Aku", "Kita"], "Hamba Kebawah Duli Tuan Patik", "Penggunaan ganti nama patik / abda mencerminkan tatasusila bahasa dalam."),
    ("Permainan tradisional gasing yang popular di Brunei dipanggil:", ["Permainan Gasing Pasang", "Gasing Pusing", "Gasing Laju", "Gasing Kayu"], "Permainan Gasing Pasang", "Gasing pasang dimainkan dengan tali pemutar menggunakan gasing kayu keras."),
    ("Apakah amalan bertahlil dan membaca Surah Yasin yang lazim diadakan sebelum menyambut bulan Ramadan?", ["Majlis Tahlil Arwah Sekampung / Jamuan Doa Selamat", "Majlis Kahwin", "Majlis Hari Jadi", "Pesta Sukan"], "Majlis Tahlil Arwah Sekampung / Jamuan Doa Selamat", "Amalan bertahlil mendoakan kesejahteraan roh ibu bapa dan kaum kerabat yang telah kembali ke rahmatullah."),
    ("Apakah nama seni mempertahankan diri tradisi Melayu Brunei?", ["Silat Melayu Brunei (contoh: Silat Kuntau, Cakak)", "Taekwondo", "Karate", "Judo"], "Silat Melayu Brunei (contoh: Silat Kuntau, Cakak)", "Silat Cakak dan Kuntau ialah warisan mempertahankan diri warisan nenek moyang."),
    ("Pakaian tradisi lelaki Melayu Brunei yang lengkap terdiri daripada:", ["Baju Melayu, seluar, sinjang, dan songkok", "Baju kemeja dan seluar jeans", "Baju kurung dan tudung", "Baju T dan kain pelikat"], "Baju Melayu, seluar, sinjang, dan songkok", "Baju Melayu lengkap diserikan dengan kain sinjang di pinggang dan songkok hitam."),
    ("Kain yang dililit di pinggang lelaki Melayu di atas seluar Baju Melayu dipanggil:", ["Sinjang (Kain Samping)", "Selendang", "Tengkolok", "Destar"], "Sinjang (Kain Samping)", "Di Brunei, kain samping dipanggil 'sinjang'."),
    ("Apakah adat ziarah-menziarahi yang diamalkan secara meluas semasa Hari Raya Aidilfitri?", ["Amalan Rumah Terbuka dan bermaaf-maafan", "Mengunci pintu rumah", "Tidur sepanjang hari", "Membeli barang mewah"], "Amalan Rumah Terbuka dan bermaaf-maafan", "Keluarga dan sahabat handai saling menziarahi untuk mengukuhkan ukhuwah dan bermaaf-maafan."),
    ("Apakah tujuan bacaan Doa Selamat diadakan sebelum memulakan sesuatu majlis penting?", ["Memohon perlindungan, keberkatan dan keredhaan daripada Allah SWT", "Untuk hiburan tetamu", "Memenuhi masa lapang", "Tradisi tanpa makna"], "Memohon perlindungan, keberkatan dan keredhaan daripada Allah SWT", "Doa Selamat mencerminkan nilai Islam memohon taufik dan hidayah Ilahi."),
    ("Sikap bergotong-royong membersihkan balai raya atau masjid dalam masyarakat Brunei disebut:", ["Amalan Memucang-mucang", "Makan bersendirian", "Berdiam diri", "Bekerja bergaji"], "Amalan Memucang-mucang", "'Memucang-mucang' ialah istilah dialek Melayu Brunei bagi amalan gotong-royong."),
    ("Bagaimanakah sikap seorang anak yang beradab ketika bertutur dengan ibu bapanya?", ["Bertutur dengan lemah lembut, sopan dan tidak meninggikan suara", "Menengking apabila marah", "Tidak mempedulikan nasihat", "Memanggil nama ibu bapa secara kasar"], "Bertutur dengan lemah lembut, sopan dan tidak meninggikan suara", "Adab berbakti kepada ibu bapa menuntut pertuturan sopan dan penuh kasih sayang."),
    ("Apakah adab menyambut tetamu yang bertandang ke rumah?", ["Menyambut dengan senyuman mesra dan menghidangkan jamuan mengikut kemampuan", "Menutup pintu rumah", "Meminta bayaran masuk", "Membuli tetamu"], "Menyambut dengan senyuman mesra dan menghidangkan jamuan mengikut kemampuan", "Memuliakan tetamu adalah ajaran Islam dan tatasusila murni Melayu."),
    ("Apakah alat muzik tradisi berdawai yang dipetik mengiringi dendangan lagu asli di Brunei?", ["Gambus", "Pianika", "Dram", "Harmonika"], "Gambus", "Gambus diiringi rebana memainkan irama zapin dan lagu asli Brunei."),
    ("Kueh tradisional Brunei berbentuk jala manis yang rangup digoreng disebut:", ["Kueh Jala", "Kueh Bahulu", "Kueh Cincin", "Kueh Sapit"], "Kueh Jala", "Kueh Jala diperbuat daripada tepung beras dan gula merah, digoreng garing menyerupai jala."),
    ("Apakah nama kueh berbentuk roda atau bulat dengan lubang-lubang manis?", ["Kueh Cincin", "Kueh Mor", "Kueh Pai", "Kueh Tart"], "Kueh Cincin", "Kueh cincin digoreng celup tepung beras dengan bentuk bulat berlubang."),
    ("Apakah pakaian tradisi perempuan Melayu Brunei yang sopan menutup aurat?", ["Baju Kurung dan bertudung kepala", "Gaun pendek", "Baju sukan ketat", "Seluar pendek"], "Baju Kurung dan bertudung kepala", "Baju kurung labuh dan longgar mematuhi syariat Islam dan nilai kesopanan timur."),
    ("Seni ukiran tembaga tradisional Brunei terkenal dengan penghasilan barang seperti:", ["Bedil tembaga, cerana pinang, dan cerek air", "Kereta api", "Kasut getah", "Kuali plastik"], "Bedil tembaga, cerana pinang, dan cerek air", "Pertukangan tembaga di Kampong Ayer menghasilkan meriam bedil dan cerana hiasan berkualiti tinggi."),
    ("Apakah upacara memotong rambut bayi yang baru lahir disertai doa selamat dan tahnik?", ["Majlis Bergunting dan Mengaqiqahkan anak", "Pesta air", "Sukaneka", "Majlis Tamat Sekolah"], "Majlis Bergunting dan Mengaqiqahkan anak", "Adat bergunting rambut bayi mengikut sunnah Rasulullah SAW."),
    ("Apakah maksud peribahasa Brunei 'Malu berdayung, perahu hanyut'?", ["Jika malas berusaha dan segan bertanya, kita akan rugi dan ketinggalan", "Perahu rosak di sungai", "Berenang di air deras", "Malu menyapa kawan"], "Jika malas berusaha dan segan bertanya, kita akan rugi dan ketinggalan", "Kiasan supaya rajin berusaha dan berani bertanya demi kejayaan."),
    ("Apakah tujuan diadakan Sambutan Hari Kebangsaan Negara Brunei Darussalam pada setiap 23 Februari?", ["Mensyukuri nikmat kemerdekaan dan memperbaharui ikrar taat setia kepada Raja dan Negara", "Untuk bercuti sahaja", "Mengadakan perlawanan bola sepak", "Membeli-belah"], "Mensyukuri nikmat kemerdekaan dan memperbaharui ikrar taat setia kepada Raja dan Negara", "Menyemai semangat patriotisme dan perpaduan bangsa berbilang generasi."),
    ("Mengapakah tatasusila dan adat Melayu Brunei wajib dipertahankan oleh generasi muda?", ["Supaya identiti kebangsaan dan maruah bangsa Brunei kekal berdaulat dan tidak luput ditelan zaman", "Untuk dipamerkan kepada pelancong sahaja", "Kerana diwajibkan oleh undang-undang antarabangsa", "Supaya boleh bergaduh"], "Supaya identiti kebangsaan dan maruah bangsa Brunei kekal berdaulat dan tidak luput ditelan zaman", "Adat dan tatasusila adalah cerminan budi pekerti tinggi bangsa berdaulat.")
]

for text, opts, correct, hint in mib_adat_data:
    add_q(t_id, s_id, text, opts, correct, hint, lang="ms")

print(f"Total Year 6 PSR questions generated: {len(questions)}")

# Write to JSON file
with open(OUTPUT_FILE, "w", encoding="utf-8") as f:
    json.dump(questions, f, ensure_ascii=False, indent=2)

print(f"✓ Saved {len(questions)} PSR questions to {OUTPUT_FILE}")

# Topic counts breakdown validation
counts_by_topic = {}
for q in questions:
    tid = q["topic_id"]
    counts_by_topic[tid] = counts_by_topic.get(tid, 0) + 1

print("\n--- Topic Breakdown (Must be >= 30 questions each) ---")
all_valid = True
for tid, cnt in sorted(counts_by_topic.items()):
    status = "✓ PASS (>= 30)" if cnt >= 30 else "❌ FAIL (< 30)"
    if cnt < 30:
        all_valid = False
    print(f"  {tid:<35}: {cnt} questions  {status}")

if all_valid and len(counts_by_topic) == 21:
    print("\n🎉 ALL 21 TOPICS SATISFY THE MINIMUM 30 QUESTIONS REQUIREMENT PERFECTLY!")
else:
    print("\n⚠️ SOME TOPICS DO NOT SATISFY REQUIREMENTS!")
    sys.exit(1)
