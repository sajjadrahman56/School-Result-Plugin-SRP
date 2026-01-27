# 📘 1️⃣ Project Overview

### 🎯 Purpose

এই system ও website টি একটি **Bangladeshi Private Primary / Pre-Cadet School** এর জন্য তৈরি করা, যেখানে—

* Guardian সহজে school information পাবে
* Result ও tuition fee status দেখতে পারবে
* School office সহজে academic + fee manage করতে পারবে
* Student data year-wise safely maintain হবে

---

## 2️⃣ User Roles

### 👨‍👩‍👧 Guardian / Parent

* Login করে student info দেখবে
* Tuition fee paid / due দেখবে
* Previous years result দেখবে
* PDF marksheet download করবে

---

### 👨‍💼 Admin (Office / Head Teacher)

* Student admission
* Class promotion
* Result publish
* Tuition fee update
* Password reset

---

### 👩‍🏫 Teacher (Optional Phase)

* Marks entry
* Result review

---

## 3️⃣ Public Website Pages (Frontend)

---

### 🏠 Home Page

**Components:**

* School Name + Logo
* Image slider (students / campus)
* Admission Open notice
* Latest 3 notices
* Quick Result button
* Guardian Login button

📌 Goal: *Trust + clarity in 10 seconds*

---

### ℹ️ About School

* School history
* Vision & Mission
* Head Teacher message
* School rules (short)

---

### 🎓 Academics

* Class list (Play → Class 5)
* Subject list per class
* Exam system (Half Yearly / Annual)

Example:

```
Class 3
Subjects: Bangla, English, Math, Science, BGS
```

---

### 👩‍🏫 Teachers

* Teacher photo
* Name
* Designation
* Qualification

---

### 📢 Notice Board

* Exam notice
* Holiday notice
* Result publish notice
* PDF download option

---

### 🧾 Public Result Page

For non-login users:

```
Select Class
Select Exam
Enter Roll
View Result
```

📌 Mostly annual result

---

### 📨 Contact Page

* Address
* Phone number
* Google Map
* Office time

---

## 4️⃣ Guardian / Parent Portal (Core Feature)

### 🔐 Login System

* Login ID: **Registration ID**
* Password: Simple numeric / text
* Given by school (printed card)

---

### 👤 Dashboard (After Login)

Shows:

* Student name
* Current class & roll
* Academic year
* Quick links

---

### 📘 Student Profile

* Name
* Father / Mother name
* DOB
* Address
* Phone
* Admission year

📌 Read-only

---

### 💰 Tuition Fee Module

#### Monthly Fee View

| Month | Amount | Status |
| ----- | ------ | ------ |
| Jan   | 500    | Paid   |
| Feb   | 500    | Due    |
| Mar   | 500    | Paid   |

* Total Due clearly shown
* No online payment initially

---

### 📊 Result History

Parent can see:

* Current year result
* Previous years result

Logic:

> Login → Reg ID → fetch all academic years

Each result:

* View
* Download PDF

---

### 🚪 Logout

---

## 5️⃣ Admin Panel Features

---

### 🧑‍🎓 Student Admission

* Create student
* Auto generate Registration ID
* Assign initial class & roll

---

### 🔄 Promotion (Year Change)

End of year:

```
Select Academic Year
Promote Class 1 → Class 2
Assign new roll
```

📌 Old data محفوظ থাকবে

---

### 📑 Result Management

* Enter marks
* Publish result
* Lock result

---

### 💵 Fee Management

* Monthly fee setup
* Mark fee as paid
* View due list

---

### 🔐 Security Rules

* Parent → view only own data
* Admin → full control
* Result once published → locked

---

# 📘 PART–2

## 🗄️ Full Database Schema (Optimized & Clean)

---

## 1️⃣ students (Permanent Info)

```
students
---------
id (PK)
registration_no (UNIQUE)
name
father_name
mother_name
dob
phone
address
admission_year
status (active/pass)
created_at
```

📌 Never changes

---

## 2️⃣ student_logins

```
student_logins
--------------
id
student_id (FK)
login_id (same as registration_no)
password
```

---

## 3️⃣ classes

```
classes
-------
id
name (Play, 1, 2, 3...)
```

---

## 4️⃣ academic_years

```
academic_years
--------------
id
year (2025)
is_active
```

---

## 5️⃣ student_academics (MOST IMPORTANT)

```
student_academics
-----------------
id
student_id (FK)
academic_year_id (FK)
class_id (FK)
section
roll_no
```

📌 Every year = new row

---

## 6️⃣ subjects

```
subjects
--------
id
name
```

---

## 7️⃣ class_subjects

```
class_subjects
--------------
class_id
subject_id
```

---

## 8️⃣ exams

```
exams
-----
id
name (Half Yearly, Annual)
academic_year_id
```

---

## 9️⃣ results

```
results
-------
id
student_academic_id (FK)
exam_id
total_marks
gpa
grade
```

---

## 🔟 fees

```
fees
----
id
class_id
academic_year_id
monthly_amount
```

---

## 1️⃣1️⃣ fee_payments

```
fee_payments
------------
id
student_academic_id
month
amount
paid_date
status (paid/due)
```

---

## 🔑 Key Design Principles (Must in Doc)

* Student identity is permanent
* Academic data is year-wise
* Roll & class are not permanent
* Parent portal auto fetch history
* No frontend calculation

---
