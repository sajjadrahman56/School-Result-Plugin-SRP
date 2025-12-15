# 📘 Project Documentation : School Result & CGPA Management System (Laravel)



## 1️⃣ Project Overview

### 🎯 Goal

বাংলাদেশের একটি **Primary / Pre-Cadet School**–এর জন্য একটি **Result Management System** তৈরি করা, যেখানে:

* Teacher সহজে marks entry করতে পারবেন
* Excel / CSV upload করে auto marks insert হবে
* Student / Guardian public result দেখতে পারবে
* CGPA + Progress Report PDF generate হবে
* Long-term maintainable (no WordPress dependency)

---

## 2️⃣ Stakeholders & User Roles

### 👤 User Types

#### 1. Super Admin

* System setup
* Class, Subject, Exam create
* Teacher create
* Result publish / lock

#### 2. Teacher (Co-Admin UI)

* Assigned class/section
* Marks entry (manual)
* Excel / CSV upload
* Draft save
* Cannot delete published result

#### 3. Public User

* Result search
* CGPA search
* PDF download / print

---

## 3️⃣ System Modules (High Level)

```
Student Management
Academic Setup
Marks Entry
Result Calculation
CGPA Calculation
PDF Generation
Public Result Portal
```

---

## 4️⃣ Version-Wise Development Plan (MOST IMPORTANT)

### 🔹 Version 0.0 – Pre-Development (No Code)

✅ Requirement Analysis
✅ Result format analysis (PDF done ✔️)
✅ Grading rules fixed
✅ Workflow documentation

📌 **Output**:
→ This document
→ Final UI references
→ Agreement on logic

---

### 🔹 Version 1.0 – Core Foundation

🎯 Objective: **System base ready করা**

#### Features

* Laravel project setup
* Authentication (Admin / Teacher)
* Database migrations:

  * students
  * classes
  * sections
  * subjects
  * exams

📌 No marks, no result yet

✅ Checkpoint:

* Admin can login
* Data insert works
* No UI polish needed

---

### 🔹 Version 2.0 – Student & Academic Setup

🎯 Objective: **Academic data ready করা**

#### Features

* Student CRUD
* Class & Section assign
* Subject assign per class
* Exam setup:

  * 1st Semester
  * 2nd Semester
  * Annual

📌 Exam full marks stored (800)

✅ Checkpoint:

* Student profile ready
* Exam visible in admin

---

### 🔹 Version 3.0 – Teacher UI (Most Critical)

🎯 Objective: **Teacher-friendly Marks Entry**

#### Teacher Dashboard

* Assigned Class / Section only
* Subject-wise mark entry grid (Excel-like)

#### Marks Entry Options

##### ✅ Option A: Manual Entry

* CQ / MCQ / PR / TUT
* Auto total calculation
* Save as draft

##### ✅ Option B: Excel / CSV Upload

* Template download
* Upload validation
* Auto insert marks

📌 **Very important**
Teacher never touches GPA / CGPA logic

✅ Checkpoint:

* Teacher can enter marks
* No calculation error

---

### 🔹 Version 4.0 – Result Calculation Engine

🎯 Objective: **Backend calculation logic**

#### Features

* Subject-wise GPA calculation
* Exam-wise result generation
* Position calculation (Class/Section)
* Lock result after publish

📌 Logic:

* Teacher → enters marks
* Admin → clicks “Publish Result”
* System → calculates everything

✅ Checkpoint:

* Annual result matches PDF

---

### 🔹 Version 5.0 – CGPA & Progress Report

🎯 Objective: **Multi-exam CGPA**

#### Features

* 1st + 2nd + Annual combined
* Total marks = 2400
* CGPA calculation
* Progress Report page

📌 Logic:

```
CGPA = (Total Obtained / Total Marks) × 100 → GPA
```

✅ Checkpoint:

* CGPA PDF matches given screenshot

---

### 🔹 Version 6.0 – PDF & Public Portal

🎯 Objective: **Final user-facing system**

#### Features

* Result search page
* CGPA search page
* PDF generation:

  * Marksheet
  * Progress report
* QR code verification

✅ Checkpoint:

* Guardian can search & print

---

## 5️⃣ Workflow Diagram (Simple Language)

### 🧑‍🏫 Teacher Workflow

```
Login
→ Select Class
→ Select Exam
→ Enter marks OR Upload Excel
→ Save Draft
```

---

### 👨‍💼 Admin Workflow

```
Login
→ Review marks
→ Publish Result
→ Lock exam
→ Generate CGPA
```

---

### 👨‍👩‍👦 Public Workflow

```
Result Page
→ Select Class / Year / Roll
→ View Result
→ Download PDF
```

---

## 6️⃣ Excel / CSV Upload Design (Teacher Friendly)

### Excel Template Columns

```
Roll | Subject Code | CQ | MCQ | PR | TUT
```

### Upload Flow

* File validation
* Preview data
* Error row highlight
* Confirm import

📌 **No overwrite without confirmation**

---

## 7️⃣ Project Rules (To Avoid Future Bugs)

❌ Teacher cannot:

* Change grading rules
* Delete published result

❌ Result once published:

* Editable only by Super Admin

✅ All calculation:

* Backend only
* No JS GPA logic

---

## 8️⃣ Documentation Maintenance Rule

Every version must have:

* Change log
* Database change note
* Rollback plan

---

## 9️⃣ What We Will NOT Do (For Safety)

❌ No WordPress
❌ No heavy JS framework
❌ No direct DB edit
❌ No calculation on frontend

---
