# توثيق واجهة برمجة التطبيقات (API) - دليل الطالب الجامعي (الإصدار 1)

## مقدمة

يوفر هذا المستند وصفًا تفصيليًا لواجهة برمجة التطبيقات (API) الخاصة بمشروع "دليل الطالب في الجامعة الوطنية الخاصة". تم تصميم هذا الـ API لتوفير البيانات اللازمة لتطبيق الموبايل الخاص بالطلاب.

**الإصدار الحالي:** `v1`

**عنوان URL الأساسي (Base URL):**

*   **بيئة التطوير:** `http://localhost:8000/api/v1` (أو المنفذ الذي تستخدمه)
*   **بيئة الإنتاج:** `https://your-api-domain.com/api/v1` (استبدل `your-api-domain.com` بالنطاق الفعلي)

**تنسيق البيانات:**

*   جميع الطلبات والاستجابات تستخدم تنسيق `JSON`.
*   يجب أن تتضمن الطلبات التي ترسل بيانات (مثل `POST`, `PUT`) رأس `Content-Type: application/json`.
*   يجب أن تتضمن الطلبات رأس `Accept: application/json` للإشارة إلى أن العميل يتوقع استجابة JSON.

**التوثيق (Authentication):**

*   نقاط النهاية (Endpoints) المذكورة أدناه والمخصصة للقراءة فقط (GET) لا تتطلب حاليًا أي توثيق. يمكن الوصول إليها بشكل عام.

**بنية الاستجابة العامة:**

*   **الاستجابات الناجحة (2xx):**
    *   للحصول على **مورد واحد** (مثل تفاصيل اختصاص): يتم إرجاع البيانات داخل كائن `data`.
        ```json
        {
            "data": {
                "id": 1,
                "name_ar": "...",
                // ... other fields
            }
        }
        ```
    *   للحصول على **مجموعة موارد** (مثل قائمة الاختصاصات): يتم إرجاع البيانات كمصفوفة داخل كائن `data`. إذا كانت النتائج مقسمة إلى صفحات (Paginated)، فسيتم تضمين كائنات `links` و `meta`.
        ```json
        // Without Pagination
        {
            "data": [
                { "id": 1, "name_ar": "...", ... },
                { "id": 2, "name_ar": "...", ... }
            ]
        }

        // With Pagination
        {
            "data": [
                { "id": 1, "name_ar": "...", ... },
                // ... more items on this page
            ],
            "links": {
                "first": "http://localhost:8000/api/v1/resource?page=1",
                "last": "http://localhost:8000/api/v1/resource?page=5",
                "prev": null,
                "next": "http://localhost:8000/api/v1/resource?page=2"
            },
            "meta": {
                "current_page": 1,
                "from": 1,
                "last_page": 5,
                "links": [ /* ... */ ],
                "path": "http://localhost:8000/api/v1/resource",
                "per_page": 15,
                "to": 15,
                "total": 70
            }
        }
        ```
*   **الاستجابات غير الناجحة (4xx, 5xx):**
    *   عادةً ما يتم إرجاع كائن JSON يحتوي على رسالة خطأ، وقد يحتوي على تفاصيل إضافية في كائن `errors` (خاصة لأخطاء التحقق 422).
        ```json
        // Example 404 Not Found
        {
            "message": "Resource not found."
        }

        // Example 400 Bad Request
        {
            "message": "Missing required parameter 'q'."
        }

        // Example 422 Validation Error (From Admin Panel usually)
        {
            "message": "The given data was invalid.",
            "errors": {
                "email": [
                    "The email field is required."
                ],
                "password": [
                    "The password field is required."
                ]
            }
        }
        ```

**رموز حالة HTTP الشائعة:**

*   `200 OK`: الطلب ناجح.
*   `201 Created`: تم إنشاء المورد بنجاح (تُستخدم عادةً في استجابة لطلب `POST`).
*   `204 No Content`: الطلب ناجح ولكن لا يوجد محتوى لإرجاعه (تُستخدم عادةً في استجابة لطلب `DELETE`).
*   `400 Bad Request`: الطلب غير صحيح أو ينقصه معلمة مطلوبة.
*   `401 Unauthorized`: خطأ في التوثيق (غير مستخدم حاليًا في نقاط نهاية القراءة).
*   `403 Forbidden`: ليس لدى المستخدم المصادق عليه إذن للوصول.
*   `404 Not Found`: لم يتم العثور على المورد المطلوب.
*   `405 Method Not Allowed`: طريقة HTTP المستخدمة غير مسموح بها لهذا المسار.
*   `422 Unprocessable Entity`: خطأ في التحقق من صحة البيانات المرسلة.
*   `500 Internal Server Error`: حدث خطأ غير متوقع في الخادم.

---

## نقاط النهاية (API Endpoints)

### 1. الاختصاصات الأكاديمية (Specializations)

#### 1.1. الحصول على قائمة الاختصاصات

*   **الطريقة:** `GET`
*   **المسار:** `/specializations`
*   **الوصف:** جلب قائمة بجميع الاختصاصات الأكاديمية المتاحة.
*   **المعلمات (Parameters):** لا يوجد.
*   **الاستجابة الناجحة (200 OK):**
    ```json
    {
        "data": [
            {
                "id": 1,
                "name_ar": "هندسة المعلوماتية",
                "name_en": "Information Engineering",
                "description_ar": "وصف مختصر لهندسة المعلوماتية...",
                "description_en": "Brief description of Information Engineering..."
            },
            {
                "id": 2,
                "name_ar": "هندسة الاتصالات",
                "name_en": "Telecommunication Engineering",
                "description_ar": "وصف مختصر لهندسة الاتصالات...",
                "description_en": "Brief description of Telecommunication Engineering..."
            }
            // ... المزيد من الاختصاصات
        ]
    }
    ```

#### 1.2. الحصول على تفاصيل اختصاص محدد

*   **الطريقة:** `GET`
*   **المسار:** `/specializations/{specialization_id}`
*   **الوصف:** جلب تفاصيل اختصاص محدد بناءً على المعرف الفريد (ID) الخاص به. يمكن أن يتضمن قائمة المقررات إذا تم تحميلها في الـ Resource.
*   **المعلمات (Parameters):**
    *   `specialization_id` (Path Parameter, Required, Integer): معرّف الاختصاص.
*   **الاستجابة الناجحة (200 OK):**
    ```json
    {
        "data": {
            "id": 1,
            "name_ar": "هندسة المعلوماتية",
            "name_en": "Information Engineering",
            "description_ar": "وصف كامل لهندسة المعلوماتية...",
            "description_en": "Full description of Information Engineering...",
            "courses": [ // (اختياري، يعتمد على تحميل العلاقة في SpecializationResource)
                {
                    "id": 101,
                    "code": "CS101",
                    "name_ar": "مقدمة في البرمجة",
                    // ... other course fields
                },
                // ... other courses in this specialization
            ]
        }
    }
    ```
*   **الاستجابة غير الناجحة:**
    *   `404 Not Found`: إذا لم يتم العثور على الاختصاص بالـ ID المحدد.

#### 1.3. الحصول على مقررات اختصاص محدد

*   **الطريقة:** `GET`
*   **المسار:** `/specializations/{specialization_id}/courses`
*   **الوصف:** جلب قائمة المقررات الدراسية التابعة لاختصاص محدد.
*   **المعلمات (Parameters):**
    *   `specialization_id` (Path Parameter, Required, Integer): معرّف الاختصاص.
*   **الاستجابة الناجحة (200 OK):**
    ```json
    {
        "data": [
            {
                "id": 101,
                "code": "CS101",
                "name_ar": "مقدمة في البرمجة",
                "name_en": "Introduction to Programming",
                "semester": "خريف 2024",
                "year_level": 1,
                // يمكن تضمين معلومات مختصرة عن الأستاذ هنا
                "faculty": [
                    { "id": 5, "name_ar": "د. عمر حسن" }
                ]
            },
            {
                "id": 102,
                "code": "CS102",
                "name_ar": "بنى المعطيات",
                // ...
            }
            // ... المزيد من المقررات لهذا الاختصاص
        ]
        // قد يتضمن links و meta إذا كانت النتائج مقسمة للصفحات
    }
    ```
*   **الاستجابة غير الناجحة:**
    *   `404 Not Found`: إذا لم يتم العثور على الاختصاص بالـ ID المحدد.

---

### 2. المقررات الدراسية (Courses)

#### 2.1. الحصول على قائمة المقررات (مع فلترة وبحث)

*   **الطريقة:** `GET`
*   **المسار:** `/courses`
*   **الوصف:** جلب قائمة بالمقررات الدراسية، مع إمكانية الفلترة حسب الاختصاص والبحث بالاسم أو الرمز. النتائج عادةً مقسمة إلى صفحات.
*   **المعلمات (Query Parameters):**
    *   `specialization_id` (Optional, Integer): فلترة المقررات حسب معرّف الاختصاص. مثال: `?specialization_id=1`
    *   `search` (Optional, String): البحث في اسم المقرر (عربي/إنجليزي) أو رمزه. مثال: `?search=برمجة` أو `?search=CS2`
    *   `page` (Optional, Integer): رقم الصفحة المطلوبة (للتنقل بين الصفحات). مثال: `?page=2`
*   **الاستجابة الناجحة (200 OK):**
    ```json
    {
        "data": [
            {
                "id": 101,
                "code": "CS101",
                "name_ar": "مقدمة في البرمجة",
                "name_en": "Introduction to Programming",
                "semester": "خريف 2024",
                "year_level": 1,
                // معلومات مختصرة عن الاختصاص والأستاذ
                "specialization": {
                    "id": 1,
                    "name_ar": "هندسة المعلوماتية"
                },
                "faculty": [
                     { "id": 5, "name_ar": "د. عمر حسن" }
                ]
            }
            // ... المزيد من المقررات في هذه الصفحة
        ],
        "links": { /* ... روابط التنقل بين الصفحات ... */ },
        "meta": { /* ... معلومات عن التقسيم للصفحات ... */ }
    }
    ```

#### 2.2. الحصول على تفاصيل مقرر محدد

*   **الطريقة:** `GET`
*   **المسار:** `/courses/{course_id}`
*   **الوصف:** جلب تفاصيل كاملة لمقرر محدد، بما في ذلك الوصف، الاختصاص، الأساتذة، والموارد التعليمية المرتبطة به.
*   **المعلمات (Parameters):**
    *   `course_id` (Path Parameter, Required, Integer): معرّف المقرر.
*   **الاستجابة الناجحة (200 OK):**
    ```json
    {
        "data": {
            "id": 101,
            "code": "CS101",
            "name_ar": "مقدمة في البرمجة",
            "name_en": "Introduction to Programming",
            "description_ar": "وصف تفصيلي للمقرر...",
            "description_en": "Detailed course description...",
            "semester": "خريف 2024",
            "year_level": 1,
            "specialization": {
                "id": 1,
                "name_ar": "هندسة المعلوماتية",
                "name_en": "Information Engineering"
            },
            "faculty": [ // الأساتذة الذين يدرسون هذا المقرر (قد يكونوا أكثر من واحد)
                {
                    "id": 5,
                    "name_ar": "د. عمر حسن",
                    "name_en": "Dr. Omar Hassan",
                    "title": "أستاذ مساعد"
                    // يمكن إضافة email و office_location إذا كانت متوفرة ومطلوبة
                }
            ],
            "resources": [ // الموارد التعليمية للمقرر
                {
                    "id": 1,
                    "title_ar": "محاضرة 1 - مقدمة",
                    "title_en": "Lecture 1 - Introduction",
                    "url": "http://example.com/path/to/lecture1.pdf",
                    "type": "lecture", // 'lecture', 'training_course', 'document', 'link'
                    "description": "ملف PDF للمحاضرة الأولى",
                    "semester": "خريف 2024"
                },
                {
                    "id": 2,
                    "title_ar": "دورة تدريبية في Java",
                    "url": "http://udemy.com/course/java-basics",
                    "type": "training_course",
                    "description": "رابط لدورة خارجية",
                    "semester": "خريف 2024"
                }
                // ... المزيد من الموارد
            ]
        }
    }
    ```
*   **الاستجابة غير الناجحة:**
    *   `404 Not Found`: إذا لم يتم العثور على المقرر بالـ ID المحدد.

---

### 3. الكادر التدريسي (Faculty)

#### 3.1. الحصول على قائمة الكادر التدريسي (مع بحث)

*   **الطريقة:** `GET`
*   **المسار:** `/faculty`
*   **الوصف:** جلب قائمة بأعضاء هيئة التدريس، مع إمكانية البحث بالاسم (عربي/إنجليزي) أو اللقب. النتائج عادةً مقسمة إلى صفحات.
*   **المعلمات (Query Parameters):**
    *   `search` (Optional, String): البحث في اسم الأستاذ (عربي/إنجليزي) أو لقبه. مثال: `?search=Ahmed` أو `?search=أستاذ`
    *   `page` (Optional, Integer): رقم الصفحة المطلوبة.
*   **الاستجابة الناجحة (200 OK):**
    ```json
    {
        "data": [
            {
                "id": 1,
                "name_ar": "د. أحمد محمد",
                "name_en": "Dr. Ahmed Mohamed",
                "title": "أستاذ مساعد",
                "email": "ahmed.m@university.edu", // قد يكون null
                "office_location": "مبنى الهندسة - مكتب 201" // قد يكون null
            },
            {
                "id": 2,
                "name_ar": "د. فاطمة علي",
                "name_en": "Dr. Fatima Ali",
                "title": "أستاذ مشارك",
                "email": "fatima.a@university.edu",
                "office_location": "مبنى الهندسة - مكتب 205"
            }
            // ... المزيد من أعضاء هيئة التدريس في هذه الصفحة
        ],
        "links": { /* ... روابط التنقل بين الصفحات ... */ },
        "meta": { /* ... معلومات عن التقسيم للصفحات ... */ }
    }
    ```

#### 3.2. الحصول على تفاصيل عضو هيئة تدريس محدد

*   **الطريقة:** `GET`
*   **المسار:** `/faculty/{faculty_id}`
*   **الوصف:** جلب تفاصيل عضو هيئة تدريس محدد (إذا كانت الواجهة تتطلب عرض ملف شخصي للأستاذ). يمكن أن يتضمن قائمة بالمقررات التي يدرسها أو المشاريع التي يشرف عليها.
*   **المعلمات (Parameters):**
    *   `faculty_id` (Path Parameter, Required, Integer): معرّف عضو هيئة التدريس.
*   **الاستجابة الناجحة (200 OK):**
    ```json
    {
        "data": {
            "id": 1,
            "name_ar": "د. أحمد محمد",
            "name_en": "Dr. Ahmed Mohamed",
            "title": "أستاذ مساعد",
            "email": "ahmed.m@university.edu",
            "office_location": "مبنى الهندسة - مكتب 201",
            // اختياري: قائمة المقررات التي يدرسها حالياً (أو في فصل معين)
            "courses": [
                { "id": 105, "code": "CS201", "name_ar": "البرمجة المتقدمة", "semester": "ربيع 2025" },
                // ...
            ],
            // اختياري: قائمة المشاريع التي يشرف عليها
            "supervised_projects": [
                { "id": 10, "title_ar": "تطبيق دليل الطالب", "year": 2024 },
                // ...
            ]
        }
    }
    ```
*   **الاستجابة غير الناجحة:**
    *   `404 Not Found`: إذا لم يتم العثور على عضو هيئة التدريس بالـ ID المحدد.

---

### 4. وسائط الجامعة (University Media / Facilities)

#### 4.1. الحصول على قائمة وسائط الجامعة (مع فلترة)

*   **الطريقة:** `GET`
*   **المسار:** `/media`
*   **الوصف:** جلب قائمة بالصور والفيديوهات الخاصة بالمرافق الجامعية، مع إمكانية الفلترة حسب التصنيف أو نوع الوسيط. النتائج عادةً مقسمة إلى صفحات.
*   **المعلمات (Query Parameters):**
    *   `category` (Optional, String): فلترة الوسائط حسب التصنيف. يجب أن تتطابق القيمة تمامًا (case-sensitive قد يكون). مثال: `?category=مختبر` أو `?category=Library`
    *   `media_type` (Optional, String): فلترة حسب نوع الوسيط (`image` أو `video`). مثال: `?media_type=image`
    *   `page` (Optional, Integer): رقم الصفحة المطلوبة.
*   **الاستجابة الناجحة (200 OK):**
    ```json
    {
        "data": [
            {
                "id": 1,
                "title_ar": "مختبر الشبكات",
                "title_en": "Networking Lab",
                "description_ar": "صورة لمختبر الشبكات المجهز بأحدث التقنيات.",
                "description_en": "Image of the networking lab with modern equipment.",
                "url": "https://your-storage-url.com/media/network_lab.jpg", // الرابط الفعلي للملف
                "media_type": "image",
                "category": "مختبر"
            },
            {
                "id": 2,
                "title_ar": "جولة في الحرم الجامعي",
                "title_en": "Campus Tour",
                "description_ar": "فيديو قصير يظهر المرافق الرئيسية في الجامعة.",
                "description_en": "Short video showing main university facilities.",
                "url": "https://your-storage-url.com/media/campus_tour.mp4",
                "media_type": "video",
                "category": "عام"
            }
            // ... المزيد من الوسائط في هذه الصفحة
        ],
        "links": { /* ... روابط التنقل بين الصفحات ... */ },
        "meta": { /* ... معلومات عن التقسيم للصفحات ... */ }
    }
    ```

---

### 5. مشاريع التخرج (Graduation Projects)

#### 5.1. الحصول على قائمة مشاريع التخرج (مع فلترة وبحث)

*   **الطريقة:** `GET`
*   **المسار:** `/projects`
*   **الوصف:** جلب قائمة بمشاريع التخرج المؤرشفة، مع إمكانية الفلترة حسب الاختصاص والسنة، والبحث بالعنوان. النتائج عادةً مقسمة إلى صفحات.
*   **المعلمات (Query Parameters):**
    *   `specialization_id` (Optional, Integer): فلترة المشاريع حسب معرّف الاختصاص. مثال: `?specialization_id=1`
    *   `year` (Optional, Integer): فلترة المشاريع حسب سنة التخرج. مثال: `?year=2023`
    *   `search` (Optional, String): البحث في عنوان المشروع (عربي/إنجليزي). مثال: `?search=تطبيق ويب`
    *   `page` (Optional, Integer): رقم الصفحة المطلوبة.
*   **الاستجابة الناجحة (200 OK):**
    ```json
    {
        "data": [
            {
                "id": 1,
                "title_ar": "نظام إدارة التعلم الإلكتروني",
                "title_en": "E-Learning Management System",
                "year": 2023,
                "semester": "ربيع",
                "student_name": "اسم الطالب (اختياري)", // قد يكون null
                "abstract_ar": "ملخص قصير للمشروع...", // قد يكون null
                "abstract_en": "Short project abstract...", // قد يكون null
                // معلومات مختصرة عن الاختصاص والمشرف
                "specialization": {
                    "id": 1,
                    "name_ar": "هندسة المعلوماتية"
                },
                "supervisor": { // قد يكون null إذا لم يتم تحديده
                    "id": 1,
                    "name_ar": "د. أحمد محمد"
                }
            }
            // ... المزيد من المشاريع في هذه الصفحة
        ],
        "links": { /* ... روابط التنقل بين الصفحات ... */ },
        "meta": { /* ... معلومات عن التقسيم للصفحات ... */ }
    }
    ```

#### 5.2. الحصول على تفاصيل مشروع تخرج محدد

*   **الطريقة:** `GET`
*   **المسار:** `/projects/{project_id}`
*   **الوصف:** جلب تفاصيل كاملة لمشروع تخرج محدد (إذا كانت الواجهة تتطلب عرض الملخص أو تفاصيل أخرى).
*   **المعلمات (Parameters):**
    *   `project_id` (Path Parameter, Required, Integer): معرّف المشروع.
*   **الاستجابة الناجحة (200 OK):**
    ```json
    {
        "data": {
            "id": 1,
            "title_ar": "نظام إدارة التعلم الإلكتروني",
            "title_en": "E-Learning Management System",
            "year": 2023,
            "semester": "ربيع",
            "student_name": "اسم الطالب الفعلي",
            "abstract_ar": "ملخص تفصيلي للمشروع وأهدافه والتقنيات المستخدمة...",
            "abstract_en": "Detailed abstract of the project, objectives, and technologies used...",
            "specialization": {
                "id": 1,
                "name_ar": "هندسة المعلوماتية",
                "name_en": "Information Engineering"
            },
            "supervisor": { // قد يكون null
                "id": 1,
                "name_ar": "د. أحمد محمد",
                "name_en": "Dr. Ahmed Mohamed",
                "title": "أستاذ مساعد"
                // يمكن إضافة email و office_location إذا كانت متوفرة ومطلوبة
            }
        }
    }
    ```
*   **الاستجابة غير الناجحة:**
    *   `404 Not Found`: إذا لم يتم العثور على المشروع بالـ ID المحدد.

---

### 6. البحث الشامل (Global Search)

#### 6.1. إجراء بحث شامل

*   **الطريقة:** `GET`
*   **المسار:** `/search`
*   **الوصف:** إجراء بحث شامل عبر الاختصاصات، المقررات، أعضاء هيئة التدريس، ومشاريع التخرج باستخدام مصطلح بحث واحد.
*   **المعلمات (Query Parameters):**
    *   `q` (Required, String): مصطلح البحث المراد استخدامه. مثال: `?q=هندسة` أو `?q=data`
*   **الاستجابة الناجحة (200 OK):**
    *   ترجع كائن يحتوي على قوائم بالنتائج المطابقة من كل قسم (قد تكون القوائم فارغة). يتم تحديد عدد النتائج لكل قسم من طرف الخادم (مثلاً، أول 10 نتائج مطابقة).
    ```json
    {
        "specializations": { // نتائج مطابقة من الاختصاصات
            "data": [
                { "id": 1, "name_ar": "هندسة المعلوماتية", ... },
                { "id": 2, "name_ar": "هندسة الاتصالات", ... }
            ]
        },
        "courses": { // نتائج مطابقة من المقررات
            "data": [
                { "id": 102, "code": "CS102", "name_ar": "بنى المعطيات", ... },
                 { "id": 305, "code": "CS305", "name_ar": "قواعد المعطيات", ... }
            ]
        },
        "faculty": { // نتائج مطابقة من الكادر التدريسي
            "data": [
                 { "id": 1, "name_ar": "د. أحمد محمد", ... }
            ]
        },
        "projects": { // نتائج مطابقة من مشاريع التخرج
            "data": [
                 { "id": 5, "title_ar": "تحليل البيانات الضخمة", "year": 2022, ... }
            ]
        }
    }
    ```
    *   *ملاحظة:* تم تعديل البنية المتوقعة هنا لتتوافق بشكل أفضل مع استخدام `ResourceCollection` لكل قسم.
*   **الاستجابة غير الناجحة:**
    *   `400 Bad Request`: إذا لم يتم توفير المعلمة `q`.

---