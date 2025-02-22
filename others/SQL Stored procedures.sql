-- Stored procedure to Update users whose password update date is older than 1 month
CREATE PROCEDURE UpdateUserStatus () BEGIN
UPDATE user
SET
    status = 'I'
WHERE
    updated_pwd_at IS NULL
    OR updated_pwd_at <= DATE_SUB (NOW (), INTERVAL 1 MONTH);

END;

SHOW PROCEDURE STATUS
WHERE
    Name = 'UpdateUserStatus';

-- DROP PROCEDURE IF EXISTS UpdateUserStatus;