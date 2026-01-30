# 🧠 Core Idea (

👉 **Student ≠ Student of a year**

একজন student:

* অনেক বছর পড়বে
* প্রতি বছর class + roll change হবে
* কিন্তু তার basic info একই থাকবে

তাই আমরা **Student** আর **Student Academic Year** আলাদা করবো।

---

## 1️⃣ students (Permanent Identity)

👉 একবার create হবে, কখনো delete না

```sql
students
---------
id (PK)
name
religion
blood_group
father_name
mother_name
phone
address
created_at
```

📌 এখানে **roll, class, year নাই**

কারণ এগুলো change হয়।

---

## 2️⃣ academic_years

```sql
academic_years
---------
id (PK)
year   -- 2025, 2026
is_active
```

---

## 3️⃣ student_sessions (MOST IMPORTANT TABLE)

👉 এই table সব fix করে দেবে

```sql
student_sessions
---------
id (PK)
student_id (FK)
academic_year_id (FK)
class_id
section
roll
```

📌 মানে:

> “এই student এই বছরে এই class-এ এই roll”

---

### 🔁 Example

#### Student: HISAN

| year | class   | roll |
| ---- | ------- | ---- |
| 2025 | Play    | 1    |
| 2026 | Nursery | 3    |
| 2027 | One     | 5    |

➡️ student_sessions এ 3টা row
➡️ students table unchanged ✅

---

## 4️⃣ exams

```sql
exams
---------
id (PK)
name        -- 1st Term, 2nd Term, Final
academic_year_id
```

📌 future-proof:

* unit test
* model test
* half yearly

সব add করা যাবে।

---

## 5️⃣ subjects

```sql
subjects
---------
id (PK)
class_id
name
full_marks
```

📌 class-wise subject mapping clean থাকে।

---

## 6️⃣ results (Heart of the system ❤️)

```sql
results
---------
id (PK)
student_session_id (FK)
exam_id (FK)
subject_id (FK)
marks
```

📌 **No GPA, No Total here**

সব calculation dynamic।

---

# 🔄 Result Page (যেটা তুমি screenshot দেখিয়েছো)

### Step 1: Identify student

```text
roll + class + section + year
```

➡️ student_session_id পাওয়া যাবে

---

### Step 2: Get exam

```text
exam_id = Final Exam (2025)
```

---

### Step 3: Get results

```sql
SELECT *
FROM results
WHERE student_session_id = ?
AND exam_id = ?
```

---

### Step 4: Calculate

* Total Marks = SUM(marks)
* GPA = AVG(grade_point)
* Grade = based on GPA
* Merit Position = rank within class & exam

👉 DB safe, logic flexible

---

## 🧮 Grade Calculation (Runtime)

```php
function gradePoint($marks) {
  if ($marks >= 80) return 5.0;
  if ($marks >= 70) return 4.0;
  if ($marks >= 60) return 3.5;
  if ($marks >= 50) return 3.0;
  if ($marks >= 40) return 2.0;
  if ($marks >= 33) return 1.0;
  return 0.0;
}
```

---

## 🧱 Why this design will NEVER break

| Scenario          | Safe? |
| ----------------- | ----- |
| New exam added    | ✅     |
| Student promoted  | ✅     |
| Roll changed      | ✅     |
| Year changed      | ✅     |
| Result re-publish | ✅     |
| Student TC        | ✅     |
| Result correction | ✅     |

---

## 🏫 How to explain to teachers/admin (simple line)

> “Student table only keeps personal info.
> Every year we create a new session for the student.
> Exams and results are attached to that session.”

---