# Quick Start Guide - School Result Plugin

## Installation (5 minutes)

1. **Download or Clone the Plugin**
   ```bash
   git clone https://github.com/sajjadrahman56/School-Result-Plugin-SRP-.git
   ```

2. **Upload to WordPress**
   - Copy the plugin folder to `/wp-content/plugins/`
   - Or ZIP the folder and upload via WordPress admin

3. **Activate the Plugin**
   - Go to WordPress Admin → Plugins
   - Find "School Result Plugin (SRP)"
   - Click "Activate"

4. **Verify Installation**
   - Look for "School Result" menu in WordPress admin sidebar
   - Database tables will be created automatically

## First Time Setup (10 minutes)

### Step 1: Add a Term
1. Go to **School Result → Terms → Add New**
2. Fill in:
   - Term Name: "First Term Exam"
   - Year: "2024"
   - Status: "Active"
3. Click "Add Term"

### Step 2: Add Subjects
1. Go to **School Result → Subjects → Add New**
2. Add these subjects:
   - Subject: "English", Code: "ENG", Class: "Class 10", Full Marks: 100, Pass Marks: 33
   - Subject: "Mathematics", Code: "MATH", Class: "Class 10", Full Marks: 100, Pass Marks: 33
   - Subject: "Science", Code: "SCI", Class: "Class 10", Full Marks: 100, Pass Marks: 33

### Step 3: Add Students
1. Go to **School Result → Students → Add New**
2. Fill in student details:
   - Name: "John Doe"
   - Roll: "001"
   - Class: "Class 10"
   - Section: "A"
3. Upload a photo (optional)
4. Click "Add Student"
5. Repeat for more students

### Step 4: Enter Results
1. Go to **School Result → Results → Add New**
2. Select:
   - Student: "John Doe"
   - Term: "First Term Exam"
   - Subject: "English"
   - Marks: "85"
3. Click "Add Result"
4. Repeat for all subjects and students

### Step 5: Calculate Positions
1. Go to **School Result → Results**
2. Click on "View Results" for your term
3. Click "Calculate Positions"
4. Rankings will be displayed automatically!

## Display Results on Frontend (2 minutes)

### Show Student Result
Add this shortcode to any page/post:
```
[srp_student_result student_id="1" term_id="1"]
```

### Show Class Rankings
Add this shortcode to display top 10 students:
```
[srp_term_rankings term_id="1" class="Class 10" limit="10"]
```

### Show Student Card
Add this shortcode for student profile:
```
[srp_student_card student_id="1"]
```

## Understanding the Grading System

The plugin uses **Bangladeshi GPA 5.0 Scale**:

| Marks Range | Grade | GPA |
|-------------|-------|-----|
| 80-100      | A+    | 5.0 |
| 70-79       | A     | 4.0 |
| 60-69       | A-    | 3.5 |
| 50-59       | B     | 3.0 |
| 40-49       | C     | 2.0 |
| 33-39       | D     | 1.0 |
| 0-32        | F     | 0.0 |

**Important:** If a student fails (F grade) in any subject, their overall GPA becomes 0.0

## Common Tasks

### How to Edit a Student
1. Go to **School Result → Students**
2. Click "Edit" next to the student
3. Make changes and click "Update Student"

### How to View a Student's Complete Results
1. Go to **School Result → Dashboard**
2. View student ID from Students page
3. Use shortcode: `[srp_student_result student_id="X" term_id="Y"]`

### How to Change a Result
1. Go to **School Result → Results → Add New**
2. Enter same student, term, and subject
3. Enter new marks
4. System will automatically update the existing result

### How to Generate Rankings
1. Enter all results for a term
2. Go to **School Result → Results**
3. Select the term
4. Click "Calculate Positions"

## Tips for Best Results

1. **Enter All Results Before Calculating Positions**
   - Positions are calculated based on complete data
   - Recalculate after adding new results

2. **Use Consistent Class Names**
   - Use "Class 10" not "class 10" or "Class X"
   - This ensures proper filtering

3. **Upload Student Photos**
   - Photos make the display more professional
   - Recommended size: 300x300px
   - Supported formats: JPG, PNG, GIF

4. **Keep Terms Organized**
   - Mark completed terms as "Completed"
   - Keep only active term as "Active"

5. **Regular Backups**
   - The plugin stores data in WordPress database
   - Regular WordPress backups protect your data

## Troubleshooting

### Results Not Showing
- Verify student ID and term ID are correct
- Check if results are entered for that student and term

### Positions Not Calculated
- Click "Calculate Positions" button
- Ensure all students have results entered

### Photo Not Uploading
- Check file size (should be under 2MB)
- Verify file format (JPG, PNG, GIF only)
- Check WordPress upload directory permissions

### GPA Seems Wrong
- Verify marks are entered correctly
- Remember: F in any subject = 0.0 overall GPA
- Check if subject full marks are configured correctly

## Getting Help

- Read the full [README.md](README.md) for complete documentation
- Check [DEVELOPER.md](DEVELOPER.md) for technical details
- Review [FEATURES.md](FEATURES.md) for all capabilities
- Open an issue on GitHub for support

## Next Steps

Now that you're set up, you can:
1. ✅ Import more students and subjects
2. ✅ Enter results for multiple terms
3. ✅ Create pages to display results publicly
4. ✅ Customize the look with CSS
5. ✅ Export or print results as needed

Happy grading! 🎓
