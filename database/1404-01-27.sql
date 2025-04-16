-- اپدیت اسامی جداول پایگاه داده
RENAME TABLE
  consultation_requests TO reservation_requests,
  consultation_requests_moshavereh TO contact_messages,
  dbo_add_doctors TO reservation_doctor_profiles,
  dbo_schedule_nobat TO reservation_schedule_slots,
  doctor_schedule TO reservation_doctor_schedules,
  dbo_user_comments TO user_feedback,
  dental_health_responses TO health_questions_responses,
  wh_users TO site_users,
  site_settings TO slider_details;