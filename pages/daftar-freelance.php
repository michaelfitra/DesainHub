<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Sebagai Freelancer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .section-title {
            font-weight: bold;
            font-size: 1.5rem;
            margin-top: 20px;
            color: #2b6777;
            margin-bottom: 20px;
        }
        .added-item {
            background-color: #e7f3f5;
            padding: 5px 10px;
            border-radius: 5px;
            display: inline-block;
            margin: 5px 5px 0 0;
            color: #2b6777;
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center mb-4">Register as a Freelancer</h2>

        <form>
            <!-- Section 1: Personal Info -->
            <div class="section-title">1. Personal Info</div>
            <div class="mb-4">
                <label for="fullName" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="fullName" required>
            </div>
            <div class="mb-4">
                <label for="displayName" class="form-label">Display Name</label>
                <input type="text" class="form-control" id="displayName" required>
            </div>
            <div class="mb-4">
                <label for="profilePhoto" class="form-label">Profile Photo</label>
                <input type="file" class="form-control" id="profilePhoto" accept="image/*">
            </div>
            <div class="mb-4">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" rows="3" required></textarea>
            </div>
            <div class="mb-4">
                <label for="language" class="form-label">Languages</label>
                <div id="languages-list" class="mb-2"></div>
                <input type="text" class="form-control" id="language" placeholder="Enter a language">
                <button type="button" class="btn btn-secondary btn-sm mt-2" onclick="addLanguage()">Add Language</button>
            </div>

            <!-- Section 2: Professional Info -->
            <div class="section-title">2. Professional Info</div>
            <div class="mb-4">
                <label for="occupation" class="form-label">Occupation</label>
                <input type="text" class="form-control" id="occupation" required>
            </div>
            <div class="mb-4">
                <label for="skills" class="form-label">Skills</label>
                <div id="skills-list" class="mb-2"></div>
                <input type="text" class="form-control" id="skills" placeholder="Enter a skill">
                <button type="button" class="btn btn-secondary btn-sm mt-2" onclick="addSkill()">Add Skill</button>
            </div>
            <div class="mb-4">
                <label for="education" class="form-label">Education</label>
                <div id="education-list" class="mb-2"></div>
                <input type="text" class="form-control" id="education" placeholder="e.g., Bachelor’s Degree in Graphic Design">
                <button type="button" class="btn btn-secondary btn-sm mt-2" onclick="addEducation()">Add Education</button>
            </div>
            <div class="mb-4">
                <label for="certifications" class="form-label">Certifications</label>
                <div id="certifications-list" class="mb-2"></div>
                <input type="text" class="form-control" id="certifications" placeholder="e.g., Certified Web Developer">
                <button type="button" class="btn btn-secondary btn-sm mt-2" onclick="addCertification()">Add Certification</button>
            </div>
            <div class="mb-4">
                <label for="personalWebsite" class="form-label">Personal Website Link</label>
                <input type="url" class="form-control" id="personalWebsite" placeholder="http://example.com">
            </div>

            <!-- Section 3: Account Security -->
            <div class="section-title">3. Account Security</div>
            <div class="mb-4">
                <label for="email" class="form-label">Email Verification</label>
                <input type="email" class="form-control" id="email" placeholder="Your verified email address" required>
                <button type="button" class="btn btn-secondary btn-sm mt-2">Verify Email</button>
            </div>
            <div class="mb-4">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="tel" class="form-control" id="phone" placeholder="+1234567890">
                <button type="button" class="btn btn-secondary btn-sm mt-2">Add Phone Number</button>
            </div>

            <!-- Submit Button -->
            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary">Complete Registration</button>
            </div>
        </form>
    </div>

    <script>
        function addLanguage() {
            const languageInput = document.getElementById('language');
            const languageValue = languageInput.value.trim();
            if (languageValue) {
                const languageList = document.getElementById('languages-list');
                const languageItem = document.createElement('span');
                languageItem.className = 'added-item';
                languageItem.textContent = languageValue;
                languageList.appendChild(languageItem);
                languageInput.value = '';
            }
        }

        function addSkill() {
            const skillInput = document.getElementById('skills');
            const skillValue = skillInput.value.trim();
            if (skillValue) {
                const skillList = document.getElementById('skills-list');
                const skillItem = document.createElement('span');
                skillItem.className = 'added-item';
                skillItem.textContent = skillValue;
                skillList.appendChild(skillItem);
                skillInput.value = '';
            }
        }

        function addEducation() {
            const educationInput = document.getElementById('education');
            const educationValue = educationInput.value.trim();
            if (educationValue) {
                const educationList = document.getElementById('education-list');
                const educationItem = document.createElement('span');
                educationItem.className = 'added-item';
                educationItem.textContent = educationValue;
                educationList.appendChild(educationItem);
                educationInput.value = '';
            }
        }

        function addCertification() {
            const certificationInput = document.getElementById('certifications');
            const certificationValue = certificationInput.value.trim();
            if (certificationValue) {
                const certificationList = document.getElementById('certifications-list');
                const certificationItem = document.createElement('span');
                certificationItem.className = 'added-item';
                certificationItem.textContent = certificationValue;
                certificationList.appendChild(certificationItem);
                certificationInput.value = '';
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
