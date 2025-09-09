-- Database: senior_citizen_assistantship
-- This schema matches your existing database structure

-- Note: Your database already has these tables, so you don't need to import this file
-- This is just for reference to show the structure

-- Key tables in your schema:
-- user: Base user information (Id, name, DOB, city, house no., Gender, phone no, password)
-- senior: Senior citizen details (ID, pref_language, medical_cond., location, contact, u1_id)
-- volunteer: Volunteer details (ID, availability, Max_distance, skills, U_id)
-- emergency_alert: Emergency alerts (ID, senior_id, alert_status, location, date, time)
-- request: Assistance requests (req_id, date, status, type_assistance, s_id)
-- schedule: Scheduling (ID, name, date, v_id, request_id)
-- feedback: User feedback (serial_no, rating, comment, user1_id)
-- notification: Notifications (ID, time, message, date, user_N_id)
-- matches_with: Volunteer-senior matching (volunteer_id, senior_id)

-- The project will now work with your existing database structure

