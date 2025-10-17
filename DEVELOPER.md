# School Result Plugin - Developer Documentation

## Architecture Overview

The School Result Plugin follows a modular, object-oriented architecture with clear separation of concerns.

### Core Components

#### 1. Main Plugin Class (`School_Result_Plugin`)
- Singleton pattern implementation
- Handles plugin initialization
- Manages hooks and filters
- Coordinates all plugin components

#### 2. Database Layer (`SRP_Database`)
- Creates and manages database tables
- Handles schema creation on activation
- Provides cleanup on uninstall

#### 3. Admin Interface (`SRP_Admin`)
- Manages WordPress admin menus
- Handles form submissions
- Provides admin page templates
- Implements CRUD operations

#### 4. Data Models
- **SRP_Student**: Student management
- **SRP_Term**: Exam term management
- **SRP_Result**: Result entry and retrieval

#### 5. Business Logic (`SRP_Calculator`)
- GPA calculations based on Bangladeshi standards
- Position ranking algorithms
- Term summary calculations

#### 6. Frontend Display (`SRP_Shortcodes`)
- Shortcode registration and handling
- Frontend template rendering

## Database Schema

### Table: `wp_srp_students`
Stores student information including personal details and photos.

```sql
- id (Primary Key)
- student_name
- student_roll (Unique with class)
- student_class
- student_section
- student_photo (URL)
- student_email
- student_phone
- date_of_birth
- created_at
- updated_at
```

### Table: `wp_srp_terms`
Manages exam terms/periods.

```sql
- id (Primary Key)
- term_name
- term_year
- term_start_date
- term_end_date
- term_status (active, completed, upcoming)
- created_at
```

### Table: `wp_srp_subjects`
Stores subject information for different classes.

```sql
- id (Primary Key)
- subject_name
- subject_code (Unique with class)
- subject_class
- full_marks
- pass_marks
- is_optional
- created_at
```

### Table: `wp_srp_results`
Individual subject results for students in specific terms.

```sql
- id (Primary Key)
- student_id (Foreign Key)
- subject_id (Foreign Key)
- term_id (Foreign Key)
- marks_obtained
- gpa
- grade
- created_at
- updated_at
```

### Table: `wp_srp_term_summary`
Aggregated results and rankings for each student per term.

```sql
- id (Primary Key)
- student_id (Foreign Key)
- term_id (Foreign Key)
- total_marks
- total_gpa
- average_gpa
- position
- grade
- created_at
- updated_at
```

## GPA Calculation Algorithm

The plugin implements the Bangladeshi GPA 5.0 scale:

```php
function calculate_gpa($marks, $full_marks) {
    $percentage = ($marks / $full_marks) * 100;
    
    if ($percentage >= 80) return ['grade' => 'A+', 'gpa' => 5.0];
    if ($percentage >= 70) return ['grade' => 'A', 'gpa' => 4.0];
    if ($percentage >= 60) return ['grade' => 'A-', 'gpa' => 3.5];
    if ($percentage >= 50) return ['grade' => 'B', 'gpa' => 3.0];
    if ($percentage >= 40) return ['grade' => 'C', 'gpa' => 2.0];
    if ($percentage >= 33) return ['grade' => 'D', 'gpa' => 1.0];
    return ['grade' => 'F', 'gpa' => 0.0];
}
```

### Average GPA Calculation
- Calculates the mean of all subject GPAs
- **Important**: If any subject has GPA 0 (F grade), the overall GPA becomes 0

### Position Calculation
- Based on average GPA (primary)
- Total marks used as tiebreaker
- Can be calculated class-wise or across all classes

## Hooks and Filters

### Actions
```php
// Plugin activation
do_action('srp_plugin_activated');

// After student added
do_action('srp_student_added', $student_id);

// After result calculated
do_action('srp_result_calculated', $result_id);

// Position calculation complete
do_action('srp_positions_calculated', $term_id);
```

### Filters
```php
// Modify grade scale
apply_filters('srp_grade_scale', $grade_scale);

// Customize student query
apply_filters('srp_student_query', $query, $args);

// Modify shortcode output
apply_filters('srp_shortcode_output', $output, $atts);
```

## Shortcode Usage

### Student Result Display
```php
[srp_student_result student_id="1" term_id="1"]
```

### Term Rankings
```php
[srp_term_rankings term_id="1" class="Class 10" limit="10"]
```

### Student Card
```php
[srp_student_card student_id="1"]
```

## Extending the Plugin

### Add Custom Grade Scale
```php
add_filter('srp_grade_scale', function($scale) {
    // Modify the grade scale array
    return $scale;
});
```

### Add Custom Calculation Logic
```php
add_filter('srp_calculate_gpa', function($gpa_data, $marks, $full_marks) {
    // Custom GPA calculation
    return $gpa_data;
}, 10, 3);
```

### Add Custom Templates
Place custom templates in your theme:
```
wp-content/themes/your-theme/school-result-plugin/
  - student-result.php
  - term-rankings.php
  - student-card.php
```

## Security Considerations

1. **Input Sanitization**: All user inputs are sanitized using WordPress functions
2. **Nonce Verification**: All forms use nonce fields for CSRF protection
3. **Capability Checks**: Admin functions require `manage_options` capability
4. **SQL Injection Prevention**: All database queries use prepared statements
5. **XSS Prevention**: All output is escaped using appropriate WordPress functions

## Testing

### Manual Testing Checklist
- [ ] Install and activate plugin
- [ ] Add students with photos
- [ ] Create multiple terms
- [ ] Add subjects for different classes
- [ ] Enter results for students
- [ ] Calculate positions
- [ ] View results in admin
- [ ] Display results using shortcodes
- [ ] Test with different user roles
- [ ] Deactivate and reactivate plugin
- [ ] Uninstall plugin (verify cleanup)

### Test Data
Use the following test scenario:
- 5 students in Class 10
- 3 terms (First Term, Mid Term, Final)
- 6 subjects (Bangla, English, Math, Science, Social Science, Religion)
- Full marks: 100 for each subject

## Performance Optimization

1. **Indexing**: Database tables have proper indexes on foreign keys
2. **Caching**: Consider implementing object caching for frequently accessed data
3. **Lazy Loading**: Student photos can be lazy loaded on frontend
4. **Pagination**: Use pagination for large result sets

## Troubleshooting

### Common Issues

#### Database Tables Not Created
- Check WordPress database permissions
- Verify dbDelta is being called correctly
- Check error logs for SQL errors

#### Photos Not Uploading
- Verify upload directory permissions
- Check PHP upload_max_filesize setting
- Ensure correct file types are allowed

#### GPA Calculations Incorrect
- Verify marks are within valid range
- Check grade scale configuration
- Review calculation logic in SRP_Calculator

## Version History

### 1.0.0 (Initial Release)
- Complete plugin implementation
- Bangladeshi GPA 5.0 support
- Multiple terms support
- Student photo management
- Automatic position calculation
- Frontend shortcodes
- Responsive design

## Future Enhancements

- [ ] PDF result card generation
- [ ] SMS/Email notifications
- [ ] Bulk import via CSV
- [ ] Grade history reports
- [ ] Parent portal access
- [ ] Mobile app integration
- [ ] Multi-language support
- [ ] Advanced analytics dashboard

## Contributing

Please follow WordPress coding standards and include proper documentation for any new features.

## Support

For technical issues, please open an issue on GitHub with:
- WordPress version
- PHP version
- Steps to reproduce
- Error messages (if any)
- Screenshots (if applicable)
