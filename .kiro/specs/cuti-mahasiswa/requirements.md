# Requirements Document

## Introduction

This system manages student leave of absence (cuti akademik) and study reactivation (aktif studi) for a university. It digitizes the entire workflow — from application submission through approval to status updates — replacing the current paper-based process. The system enforces academic policies (minimum semester count, maximum leave duration, application windows) and supports four user roles: Superadmin, Admin Akademik, Admin Fakultas, and Mahasiswa.

## Glossary

- **Sistem**: The cuti-mahasiswa Laravel + Filament application
- **Mahasiswa**: A student user who submits leave or reactivation applications
- **Admin_Fakultas**: Faculty administrator who reviews and processes student applications within their assigned faculty
- **Admin_Akademik**: Academic administrator who manages academic-level operations
- **Superadmin**: System administrator who manages academic periods, application date ranges, and master data (fakultas, prodi)
- **Fakultas**: A faculty/school entity within the university (e.g., Fakultas Teknik, Fakultas Sains)
- **Prodi**: A study program (Program Studi) that belongs to a Fakultas
- **Periode_Akademik**: An academic period (semester) record containing the active semester designation and date ranges for leave/reactivation applications
- **Pengajuan_Cuti**: A leave of absence application submitted by a student
- **Pengajuan_Aktif_Studi**: A study reactivation application submitted by a student after leave ends
- **NIM**: Student identification number (Nomor Induk Mahasiswa)
- **SKS_Tempuh**: Total credits attempted by the student
- **SKS_Lulus**: Total credits passed by the student
- **Dosen_Wali**: Academic advisor assigned to the student
- **KHS**: Legalized academic transcript (Kartu Hasil Studi)
- **UKT**: Tuition fee (Uang Kuliah Tunggal)
- **Semester_Tempuh**: Number of semesters the student has completed

## Requirements

### Requirement 1: Academic Period Management

**User Story:** As a Superadmin, I want to manage academic periods and set application date ranges, so that leave and reactivation applications are only accepted within designated windows.

#### Acceptance Criteria

1. THE Sistem SHALL allow Superadmin to create, update, and view Periode_Akademik records containing academic year (format: YYYY/YYYY, e.g., 2024/2025), semester type (Ganjil/Genap), and active status
2. THE Sistem SHALL enforce that exactly one Periode_Akademik is marked as active at any given time
3. WHEN Superadmin sets a Periode_Akademik as active, THE Sistem SHALL deactivate the previously active Periode_Akademik and set the selected Periode_Akademik as active
4. THE Sistem SHALL allow Superadmin to define a start date and end date for the leave application window (tanggal buka–tutup pengajuan cuti) within a Periode_Akademik
5. THE Sistem SHALL allow Superadmin to define a start date and end date for the reactivation application window (tanggal buka–tutup pengajuan aktif studi) within a Periode_Akademik
6. IF a Superadmin attempts to save a Periode_Akademik with an end date that is equal to or before the start date for either application window, THEN THE Sistem SHALL display a validation error indicating that the end date must be after the start date, and reject the submission
7. IF a Superadmin attempts to create a Periode_Akademik with the same academic year and semester type combination as an existing record, THEN THE Sistem SHALL display a validation error indicating duplication, and reject the submission
8. IF a student submits a leave application outside the defined leave application window of the active Periode_Akademik, THEN THE Sistem SHALL reject the submission and display a message indicating the application window is closed
9. IF a student submits a reactivation application outside the defined reactivation application window of the active Periode_Akademik, THEN THE Sistem SHALL reject the submission and display a message indicating the application window is closed

### Requirement 2: Leave of Absence Application Submission

**User Story:** As a Mahasiswa, I want to submit a leave of absence application online, so that I do not need to visit the faculty office physically and fill paper forms.

#### Acceptance Criteria

1. WHEN Mahasiswa navigates to the leave application page, THE Sistem SHALL display a form containing fields for: NIM, name, Prodi, Semester_Tempuh, SKS_Tempuh, SKS_Lulus, Dosen_Wali, and reason for leave (alasan cuti)
2. WHEN the leave application form is displayed, THE Sistem SHALL auto-populate NIM, name, Prodi, Dosen_Wali, Semester_Tempuh, SKS_Tempuh, and SKS_Lulus as read-only fields from the authenticated Mahasiswa profile, leaving only the reason for leave (alasan cuti) as an editable input field
3. WHEN Mahasiswa submits a Pengajuan_Cuti, THE Sistem SHALL validate that the Mahasiswa has completed a minimum of 2 semesters (Semester_Tempuh >= 2)
4. IF Mahasiswa has completed fewer than 2 semesters, THEN THE Sistem SHALL reject the application and display the message "Mahasiswa harus telah menempuh minimal 2 semester untuk mengajukan cuti"
5. WHEN Mahasiswa submits a Pengajuan_Cuti, THE Sistem SHALL validate that the total approved leave semesters for the Mahasiswa does not exceed 2
6. IF the Mahasiswa has already used 2 semesters of leave, THEN THE Sistem SHALL reject the application and display the message "Kuota cuti maksimal 2 semester telah habis"
7. WHEN Mahasiswa submits a Pengajuan_Cuti, THE Sistem SHALL validate that the Mahasiswa did not take leave in the immediately preceding semester
8. IF the Mahasiswa took leave in the immediately preceding semester, THEN THE Sistem SHALL reject the application and display the message "Cuti tidak dapat diambil secara berturut-turut"
9. WHILE the current date is outside the leave application window defined in the active Periode_Akademik, THE Sistem SHALL hide the leave application form and display the message "Pengajuan cuti belum dibuka atau sudah ditutup"
10. WHEN the Pengajuan_Cuti passes all validation rules, THE Sistem SHALL set the application status to "Menunggu Persetujuan" and record the submission timestamp
11. WHEN Mahasiswa submits a Pengajuan_Cuti, THE Sistem SHALL validate that the reason for leave (alasan cuti) is not empty and contains between 10 and 500 characters
12. IF the Mahasiswa already has a Pengajuan_Cuti with status "Menunggu Persetujuan" for the active Periode_Akademik, THEN THE Sistem SHALL reject the new submission and display an error message indicating that a pending application already exists for this period
13. IF the reason for leave is empty or outside the allowed length, THEN THE Sistem SHALL reject the submission and display a validation error indicating the required length between 10 and 500 characters

### Requirement 3: Leave Application Processing

**User Story:** As an Admin_Fakultas, I want to review and process student leave applications, so that leave requests are handled promptly without manual paper workflows.

#### Acceptance Criteria

1. THE Sistem SHALL display a list of all Pengajuan_Cuti with status "Menunggu Persetujuan" to Admin_Fakultas, ordered by submission timestamp ascending (oldest first)
2. WHEN Admin_Fakultas views a Pengajuan_Cuti, THE Sistem SHALL display all submitted details including NIM, name, Prodi, Semester_Tempuh, SKS_Tempuh, SKS_Lulus, Dosen_Wali, reason, and submission timestamp
3. THE Sistem SHALL allow Admin_Fakultas to approve or reject a Pengajuan_Cuti with a note (catatan) of maximum 500 characters, where the note is optional when approving and mandatory when rejecting
4. WHEN Admin_Fakultas approves a Pengajuan_Cuti, THE Sistem SHALL update the application status to "Disetujui" and record the approval timestamp and approver identity
5. WHEN Admin_Fakultas approves a Pengajuan_Cuti, THE Sistem SHALL update the Mahasiswa academic status to "Cuti"
6. WHEN Admin_Fakultas rejects a Pengajuan_Cuti, THE Sistem SHALL update the application status to "Ditolak" and record the rejection timestamp, approver identity, and rejection note
7. IF Admin_Fakultas attempts to approve an application that violates any eligibility rule (semester minimum, maximum quota, or consecutive leave), THEN THE Sistem SHALL reject the action and display the corresponding validation error
8. IF Admin_Fakultas attempts to approve or reject a Pengajuan_Cuti that no longer has status "Menunggu Persetujuan", THEN THE Sistem SHALL reject the action and display an error message indicating the application has already been processed

### Requirement 4: Study Reactivation Application Submission

**User Story:** As a Mahasiswa, I want to submit a study reactivation application online after my leave period ends, so that my student status is reactivated and I can continue my studies.

#### Acceptance Criteria

1. WHEN Mahasiswa navigates to the reactivation page, THE Sistem SHALL display a form with two mandatory file upload fields: last legalized KHS file upload and UKT payment proof file upload
2. WHEN Mahasiswa submits a Pengajuan_Aktif_Studi, THE Sistem SHALL validate that the Mahasiswa currently has academic status "Cuti"
3. IF Mahasiswa does not have academic status "Cuti", THEN THE Sistem SHALL reject the application and display the message "Hanya mahasiswa dengan status Cuti yang dapat mengajukan aktif studi"
4. WHILE the current date is outside the reactivation application window defined in the active Periode_Akademik, THE Sistem SHALL hide the reactivation application form and display the message "Pengajuan aktif studi belum dibuka atau sudah ditutup"
5. WHEN the Pengajuan_Aktif_Studi is successfully submitted, THE Sistem SHALL set the application status to "Menunggu Persetujuan" and record the submission timestamp
6. IF Mahasiswa uploads a file that is not PDF, JPG, or PNG format, or exceeds 2MB in size, THEN THE Sistem SHALL reject the upload and display a validation error indicating the allowed formats and maximum file size
7. IF Mahasiswa already has a Pengajuan_Aktif_Studi with status "Menunggu Persetujuan", THEN THE Sistem SHALL reject the new submission and display a message indicating that a pending reactivation application already exists

### Requirement 5: Reactivation Application Processing

**User Story:** As an Admin_Fakultas, I want to review and process reactivation applications, so that students can resume their studies without visiting multiple offices.

#### Acceptance Criteria

1. THE Sistem SHALL display a list of all Pengajuan_Aktif_Studi with status "Menunggu Persetujuan" to Admin_Fakultas, ordered by submission timestamp in ascending order (oldest first)
2. WHEN Admin_Fakultas views a Pengajuan_Aktif_Studi, THE Sistem SHALL display the student NIM, name, Prodi, Semester_Tempuh, Dosen_Wali, uploaded KHS file, uploaded UKT payment proof file, and submission timestamp
3. THE Sistem SHALL allow Admin_Fakultas to approve or reject a Pengajuan_Aktif_Studi with an optional note (catatan) of maximum 500 characters
4. WHEN Admin_Fakultas approves a Pengajuan_Aktif_Studi, THE Sistem SHALL update the application status to "Disetujui", record the approval timestamp and approver identity, and update the Mahasiswa academic status to "Aktif"
5. WHEN Admin_Fakultas rejects a Pengajuan_Aktif_Studi, THE Sistem SHALL update the application status to "Ditolak" and record the rejection note, rejection timestamp, and approver identity
6. IF Admin_Fakultas attempts to approve or reject a Pengajuan_Aktif_Studi that no longer has status "Menunggu Persetujuan", THEN THE Sistem SHALL reject the action and display an error message indicating the application has already been processed

### Requirement 6: Role-Based Access Control

**User Story:** As a system administrator, I want users to have appropriate access levels, so that each role can only perform authorized operations.

#### Acceptance Criteria

1. THE Sistem SHALL assign each user exactly one role from the set: Superadmin, Admin_Akademik, Admin_Fakultas, or Mahasiswa
2. THE Sistem SHALL restrict access to the Periode_Akademik management module to users with the Superadmin role
3. THE Sistem SHALL restrict access to the leave and reactivation application processing views to users with Admin_Fakultas or Admin_Akademik roles
4. THE Sistem SHALL restrict leave and reactivation application submission to users with the Mahasiswa role
5. IF an unauthenticated user attempts to access any protected page, THEN THE Sistem SHALL redirect the user to the login page
6. IF an authenticated user attempts to access a page outside their role permissions, THEN THE Sistem SHALL display a 403 Forbidden response
7. THE Sistem SHALL enforce role-based restrictions at the server level (middleware/policy) so that bypassing the UI does not grant unauthorized access

### Requirement 7: Application History and Status Tracking

**User Story:** As a Mahasiswa, I want to view my leave and reactivation application history, so that I can track the progress and outcome of my submissions.

#### Acceptance Criteria

1. THE Sistem SHALL display a list of all Pengajuan_Cuti and Pengajuan_Aktif_Studi submitted by the authenticated Mahasiswa, showing for each entry: application type (Cuti or Aktif Studi), submission date, and current status (Menunggu Persetujuan, Disetujui, or Ditolak)
2. WHEN Mahasiswa views an application record, THE Sistem SHALL display the current status, submission date, application type, and the admin note recorded during approval or rejection (if present)
3. THE Sistem SHALL order the application history list by submission date in descending order (newest first)
4. IF the authenticated Mahasiswa has no submitted applications, THEN THE Sistem SHALL display an empty state message indicating no application history is available

### Requirement 8: Student Profile Data

**User Story:** As a system user, I want student profile information to be stored in the system, so that applications can auto-populate student data and enforce eligibility rules.

#### Acceptance Criteria

1. THE Sistem SHALL store Mahasiswa profile data including: NIM (unique, maximum 20 characters), name (maximum 100 characters), email (maximum 100 characters, valid email format), Prodi (foreign key to prodi table), Semester_Tempuh (integer, 1 to 14), SKS_Tempuh (integer, 0 to 160), SKS_Lulus (integer, 0 to 160), Dosen_Wali (maximum 100 characters), and academic status (one of: Aktif, Cuti, Mengundurkan Diri)
2. THE Sistem SHALL set the default academic status for new Mahasiswa records to "Aktif"
3. WHEN a Mahasiswa account is created, THE Sistem SHALL require NIM, name, email, and Prodi as mandatory fields
4. IF a Mahasiswa account creation or update is submitted with a missing mandatory field or an invalid email format, THEN THE Sistem SHALL reject the submission and display a validation error indicating the fields that failed validation
5. THE Sistem SHALL enforce that NIM is unique across all Mahasiswa records and that SKS_Lulus does not exceed SKS_Tempuh for any Mahasiswa record
6. IF a Mahasiswa account creation or update is submitted with a duplicate NIM or SKS_Lulus greater than SKS_Tempuh, THEN THE Sistem SHALL reject the submission and display a validation error indicating the constraint violation
7. THE Sistem SHALL associate each Mahasiswa with exactly one Prodi, and through the Prodi's relationship to Fakultas, the Mahasiswa is implicitly associated with a Fakultas

### Requirement 9: Faculty and Study Program Management

**User Story:** As a Superadmin, I want to manage faculty and study program master data, so that the organizational structure is properly maintained and used for scoping admin access and student associations.

#### Acceptance Criteria

1. THE Sistem SHALL allow Superadmin to create, update, and view Fakultas records containing a unique code (kode, maximum 10 characters) and name (nama, maximum 100 characters)
2. THE Sistem SHALL allow Superadmin to create, update, and view Prodi records containing a unique code (kode, maximum 10 characters), name (nama, maximum 100 characters), education level (jenjang: S1/S2/S3/D3/D4), and a foreign key reference to the parent Fakultas
3. IF a Superadmin attempts to create a Fakultas or Prodi with a duplicate kode, THEN THE Sistem SHALL reject the submission and display a validation error indicating the code already exists
4. THE Sistem SHALL require that each Prodi is associated with exactly one Fakultas
5. IF a Superadmin attempts to delete a Fakultas that still has associated Prodi records, THEN THE Sistem SHALL reject the deletion and display a message indicating that associated programs must be removed or reassigned first
6. IF a Superadmin attempts to delete a Prodi that still has associated Mahasiswa records, THEN THE Sistem SHALL reject the deletion and display a message indicating that associated students must be removed or reassigned first

### Requirement 10: Faculty-Scoped Admin Access

**User Story:** As an Admin_Fakultas, I want to only see and process applications from students within my assigned faculty, so that I do not accidentally process applications outside my jurisdiction.

#### Acceptance Criteria

1. THE Sistem SHALL associate each Admin_Fakultas user with exactly one Fakultas
2. WHEN Admin_Fakultas views the list of Pengajuan_Cuti or Pengajuan_Aktif_Studi, THE Sistem SHALL only display applications from Mahasiswa whose Prodi belongs to the Admin_Fakultas's assigned Fakultas
3. IF Admin_Fakultas attempts to approve or reject an application from a Mahasiswa outside their assigned Fakultas, THEN THE Sistem SHALL reject the action and display a 403 Forbidden response
4. THE Sistem SHALL enforce the faculty-scoped restriction at the server level (middleware/policy) so that bypassing the UI does not grant cross-faculty access
