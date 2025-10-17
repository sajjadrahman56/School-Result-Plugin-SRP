# School-Result-Plugin-SRP-
A free and open-source WordPress plugin that manages school exam results — built for Bangladeshi grading standards (GPA 5.0 scale). It supports multiple terms, class-wise student photos, and automatically computes final positions based on each term’s performance.

## ✨ Key Features

* 🧾 **CSV Import** (Students, Subjects, Exams, Marks)
* 🖼️ **Auto photo detection** from `uploads/srp_photos/{Class}/{Roll}.jpg`
* 🧮 **Bangladesh GPA (5.00 scale)** grading system
* 📊 **Final Position** = Average of all term positions
* 🔐 Secure AJAX-based frontend search
* 🧾 **Printable & PDF-ready transcript**
* 🌍 **Hacktoberfest-ready open-source project**

---

## 🇧🇩 Bangladesh GPA Grading System

| Marks Range | Letter Grade | Grade Point (GPA) |
| ----------- | ------------ | ----------------- |
| 80–100      | A+           | 5.00              |
| 70–79       | A            | 4.00              |
| 60–69       | A-           | 3.50              |
| 50–59       | B            | 3.00              |
| 40–49       | C            | 2.00              |
| 33–39       | D            | 1.00              |
| 00–32       | F            | 0.00              |

**Final GPA (per term)** = average of all subject GPAs (rounded to two decimals).
If any subject is `F`, overall GPA = **0.00 (Fail)**.

---

## 🧮 Final Result Calculation System

Each student participates in multiple term exams.
At the end of the year, the **final position** is calculated based on the **average of all term positions**.

Example:

| Term     | Position |
| -------- | -------- |
| 1st Term | 1        |
| 2nd Term | 3        |
| 3rd Term | 2        |

**Final Position = (1 + 3 + 2) ÷ 3 = 2.0 → 2nd Position Overall**

---


## 🧩 Database Overview

| Table          | Description                                            |
| -------------- | ------------------------------------------------------ |
| `srp_students` | Student details (roll, class, section, photo, parents) |
| `srp_subjects` | Subjects and their full marks                          |
| `srp_exams`    | Exam terms and year/session                            |
| `srp_marks`    | Marks per student per subject per exam                 |

---

## 🛠️ Installation

1. **Clone or download** the repository

   ```bash
   git clone https://github.com/yourusername/school-result-plugin-wp.git
   ```
2. Copy it to your WordPress `/wp-content/plugins/` directory.
3. Activate **School Result Plugin (SRP)** from *WordPress Admin → Plugins*.
4. Go to **SRP Results** → upload CSV files (Students, Subjects, Exams, Marks).
5. Optionally add photos under:

   ```
   wp-content/uploads/srp_photos/{ClassName}/{Roll}.jpg
   ```
6. Create a page and place shortcode:

   ```
   [srp_result_search]
   ```
7. Done! Students can now search results online.

---

## 📂 Folder-Based Photo Example

```
wp-content/uploads/srp_photos/
├── Four/
│   ├── 1.jpg
│   ├── 2.jpg
├── Five/
│   ├── 1.jpg
│   └── 2.jpg
```

If no matching photo found → fallback to `no-photo.png`.

---

## 📊 CSV Format Samples

### `Students.csv`

```
roll,name,father_name,mother_name,photo_url,class_name,section,group_name
1,Sidratul Muntaha,Abdul Latif,Zulekha Begum,,Four,A,General
2,Arif Hossain,Mohammad Ali,Rehana Begum,,Four,A,General
3,Sumaiya Akter,Mustafa Kamal,Sadia Akter,,Four,B,General
4,Rahim Uddin,Abdul Karim,Anwara Khatun,,Five,A,Science
5,Anas Ahmed,Imran Ahmed,Mina Begum,,Five,B,Commerce
```

### `Subjects.csv`

```
name,full_mark
Bangla,100
Bangla 2nd Part,50
English,100
Mathematics,100
General Science,100
```

### `Exams.csv`

```
name,year
1st Term Exam,2025
2nd Term Exam,2025
3rd Term Exam,2025
Final Exam,2025
Annual Exam,2024
```

### `Marks.csv`

```
roll,exam_id,subject_id,obtained_mark,highest_mark
1,1,1,89,90
1,1,2,48,48
1,1,3,97,97
1,1,4,87,87
1,1,5,98,99
```

---

## 🧾 Example Transcript Highlights

* Student photo, name, roll, section, class
* Subject-wise marks, GPA & Letter Grade
* Total marks, GPA summary
* Merit position per term + final average position
* QR verification
* Print & PDF-ready layout

---

## 🧰 Tech Stack

| Layer        | Technology                         |
| ------------ | ---------------------------------- |
| Backend      | PHP 8+, WordPress Plugin API       |
| Database     | MySQL                              |
| Frontend     | jQuery, HTML5, CSS3                |
| Optional     | Dompdf (PDF export)                |
| QR Generator | [qrickit.com](https://qrickit.com) |

---

## ⚙️ Configuration Notes

| Setting            | Description                                      |
| ------------------ | ------------------------------------------------ |
| PHP Version        | 7.4 or higher                                    |
| File Types         | CSV, ZIP, JPG, PNG, WebP                         |
| Folder Permissions | `wp-content/uploads/srp_photos/` writable        |
| Security           | Sanitized input + prepared SQL                   |
| Performance        | Caching, lazy image loading, print-optimized CSS |

---

## 🤝 Contributing

Pull Requests are always welcome ❤️

### You can contribute by:

* Improving GPA logic or F-grade handling
* Enhancing PDF export (with QR embed)
* Adding term-based average reports
* Translating to Bangla (localization)
* UI/UX improvements for transcripts

**Steps:**

1. Fork this repo
2. Create a branch → `feature/your-feature-name`
3. Commit & push
4. Open a Pull Request

Add the label `hacktoberfest-accepted` when merging 

---

## 📜 License

**MIT License** — free for education and commercial use with attribution.




---

It’ll make your README look **professional and visually appealing**.

