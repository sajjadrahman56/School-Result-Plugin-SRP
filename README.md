# School Result Plugin (SRP)

A free and open-source WordPress plugin that manages school exam results — built for Bangladeshi grading standards (GPA 5.0 scale). It supports multiple terms, class-wise student photos, and automatically computes final positions based on each term's performance.

## Features

### Core Functionality
- ✅ **Student Management** - Add, edit, and manage student records with photos
- ✅ **Multiple Terms Support** - Manage different exam terms (First Term, Mid-Term, Final, etc.)
- ✅ **Subject Management** - Create and manage subjects for different classes
- ✅ **Result Entry** - Enter marks with automatic GPA calculation
- ✅ **Automatic Position Calculation** - Ranks students based on their performance
- ✅ **Class-wise Student Photos** - Upload and display student photos

### Bangladeshi Grading System (GPA 5.0)
The plugin implements the standard Bangladeshi grading scale:
- **A+** (80-100) = GPA 5.0
- **A** (70-79) = GPA 4.0
- **A-** (60-69) = GPA 3.5
- **B** (50-59) = GPA 3.0
- **C** (40-49) = GPA 2.0
- **D** (33-39) = GPA 1.0
- **F** (0-32) = GPA 0.0

### Display Options
- Student result cards with photos
- Term-wise ranking tables
- Individual student result displays
- Shortcode support for frontend display

## Installation

1. Download the plugin ZIP file or clone the repository
2. Upload to your WordPress `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Navigate to 'School Result' in the WordPress admin menu

## Usage

### Admin Panel

#### 1. Add Students
- Go to **School Result > Students > Add New**
- Fill in student details (Name, Roll, Class, Section, etc.)
- Upload student photo (optional)
- Click 'Add Student'

#### 2. Create Terms
- Go to **School Result > Terms > Add New**
- Enter term name (e.g., "First Term", "Mid-Term", "Final")
- Set year and dates
- Choose status (Active, Completed, or Upcoming)

#### 3. Add Subjects
- Go to **School Result > Subjects > Add New**
- Enter subject name and code
- Set class, full marks, and pass marks
- Mark as optional if applicable

#### 4. Enter Results
- Go to **School Result > Results > Add New**
- Select student, term, and subject
- Enter marks obtained
- GPA and grade will be calculated automatically

#### 5. Calculate Positions
- Go to **School Result > Results**
- Select a term to view results
- Click 'Calculate Positions' to rank students

### Frontend Display

Use these shortcodes to display results on any page or post:

#### Display Student Result
```
[srp_student_result student_id="1" term_id="1"]
```

#### Display Term Rankings
```
[srp_term_rankings term_id="1" class="Class 10" limit="10"]
```

#### Display Student Card
```
[srp_student_card student_id="1"]
```

## Database Schema

The plugin creates the following tables:

- `wp_srp_students` - Student information
- `wp_srp_terms` - Exam terms
- `wp_srp_subjects` - Subject details
- `wp_srp_results` - Individual subject results
- `wp_srp_term_summary` - Aggregated term results and rankings

## File Structure

```
school-result-plugin/
├── school-result-plugin.php      # Main plugin file
├── uninstall.php                 # Cleanup on uninstall
├── includes/                     # Core plugin classes
│   ├── class-srp-database.php   # Database management
│   ├── class-srp-admin.php      # Admin interface
│   ├── class-srp-student.php    # Student management
│   ├── class-srp-term.php       # Term management
│   ├── class-srp-result.php     # Result management
│   ├── class-srp-calculator.php # GPA calculations
│   └── class-srp-shortcodes.php # Frontend shortcodes
├── templates/                    # Template files
│   ├── admin/                   # Admin templates
│   └── frontend/                # Frontend templates
└── assets/                       # Static assets
    ├── css/                     # Stylesheets
    └── js/                      # JavaScript files
```

## Requirements

- WordPress 5.0 or higher
- PHP 7.0 or higher
- MySQL 5.6 or higher

## License

This plugin is licensed under the GPL v2 or later.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Support

For issues and questions, please open an issue on GitHub.

## Credits

Developed by Sajjad Rahman

## Changelog

### Version 1.0.0
- Initial release
- Student management with photo upload
- Multiple terms support
- Subject management
- Result entry with automatic GPA calculation
- Automatic position calculation
- Frontend shortcodes for display
- Responsive design for all screen sizes
