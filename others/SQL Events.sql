-- Enable the event scheduler
SET
    GLOBAL event_scheduler = ON;

-- Event to execute the stored procedure UpdateUserStatus every day at 06:00 AM
CREATE EVENT IF NOT EXISTS DailyUpdateUserStatus ON SCHEDULE EVERY 1 DAY STARTS TIMESTAMP(CURRENT_DATE, '06:00:00') DO BEGIN CALL UpdateUserStatus ();

END;

SHOW EVENTS;

-- DROP EVENT IF EXISTS DailyUpdateUserStatus;