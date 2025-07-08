<!--! fname  -->
<!--! lname  -->
<!--! email  -->
<!--! phone  -->

CREATE TABLE stud_basic_info (
    id INT AUTO_INCREMENT PRIMARY KEY,
        fname VARCHAR(100) NOT NULL,
        lname VARCHAR(100) NOT NULL,
        email VARCHAR(150) NOT NULL UNIQUE,
        phone VARCHAR(15) NOT NULL 
);

<!--! gender -->
<!--! address1 -->
<!--! address2 -->
<!--! city  -->
<!--! state -->
<!--! country -->
<!--! zip   -->
<!--! resume -->

CREATE TABLE stud_gen_info (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
        gender ENUM('male', 'female', 'other') NOT NULL,
        address1 VARCHAR(255),
        address2 VARCHAR(255),
        city VARCHAR(100),
        state VARCHAR(100),
        country VARCHAR(100),
        zip VARCHAR(10),
        photo VARCHAR(255),
    FOREIGN KEY (student_id) REFERENCES stud_basic_info(id) ON DELETE CASCADE ON UPDATE CASCADE
);

<!--! qualification   -->
<!--! percentage -->
<!--! passing year -->
<!--! university -->

CREATE TABLE qualifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    qualification_name VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE stud_academic_info (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
        qualification_id INT NOT NULL,
        percentage DECIMAL(5,2) NOT NULL,
        passing_year YEAR NOT NULL,
        university VARCHAR(150) NOT NULL,
    FOREIGN KEY (student_id) REFERENCES stud_basic_info(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (qualification_id) REFERENCES qualifications(id) ON DELETE CASCADE ON UPDATE CASCADE
);


<!--! Hobbies -->

CREATE TABLE hobbies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hobby_name VARCHAR(100) NOT NULL UNIQUE
);



CREATE TABLE stud_hobbies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    hobby_id INT NOT NULL,
    FOREIGN KEY (student_id) REFERENCES stud_basic_info(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (hobby_id) REFERENCES hobbies(id) ON DELETE CASCADE ON UPDATE CASCADE
);




-> qualification (DYNAMIC) -> (BCA , MCA , B.COM , MCOM, B.TECH ,M.TECH , B.A , M.A , OTHERS )
IF USER CAN CLICK OTHERS THTA HE CAN SHOW INPUT FILED THAT ADD NEW qualification FOR THE USER AND THAT WAS DYNAMIC ADDITON

-> Hobbies  (Watching Movies/TV,Gaming,Writing/Blogging ,Sports ,Travel,Reading,Volunteering/Community Involvement, Learning Languages ,Photography, Playing a Musical Instrument, Graphic Design/Digital Art ,Cooking/Baking , Woodworking/DIY Projects , Coding/Programming , OTHERS ) => IF USER CAN CLICK OTHERS THTA HE CAN SHOW INPUT FILED THAT ADD NEW qualification FOR THE USER AND THAT WAS DYNAMIC ADDITON 


<!--* fname  -->
<!--* lname  -->
<!--* email  -->
<!--* phone  -->
<!--* resume(photo upload) -->
<!--* gender -->
<!--* address1 -->
<!--* address2 -->
<!--* city  -->
<!--* state -->
<!--* country -->
<!--* zip   -->
<!--* qualification (DYNAMIC) -->
<!--* percentage -->
<!--* passing year -->
<!--* university -->
<!--* Hobbies (DYNAMIC) -->