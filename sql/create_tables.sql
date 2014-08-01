CREATE TABLE IF NOT EXISTS `sections` 
  (
    `section_id` SMALLINT UNSIGNED NOT NULL,
    `course_id` VARCHAR(16) NOT NULL,
    `semester_id` SMALLINT UNSIGNED NOT NULL,
    `email` VARCHAR(255) NOT NULL
  );