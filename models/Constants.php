<?php

namespace app\models;

/**
 * Description of Constants
 *
 * @author Avuunita
 */
class Constants
{

    const STATUS_ACTIVE_DB = "A";
    const STATUS_INACTIVE_DB = "I";
    const STATUS_NULL_DB = "N";
    const STATUS_DRAFT_DB = "B";
    const STATUS_DELETED_DB = "D";

    const STATUS_APPROVED_DB = "A";
    const STATUS_REJECTED_DB = "R";
    const STATUS_PENDING_DB = "P";

    const STATUS_APPROVED = "Approved";
    const STATUS_REJECTED = "Rejected";
    const STATUS_PENDING = "Pending";

    const ROLE_ADMIN = "admin";
    const ROLE_USER = "user";

    const ROLE_OWNER_DB = "O";
    const ROLE_SUPERVISOR_DB = "S";
    const ROLE_MEMBER_DB = "M";

    const ROLE_OWNER = "Owner";
    const ROLE_SUPERVISOR = "Supervisor";
    const ROLE_MEMBER = "Member";

    const DEFAULT_USER_CREATE = 1;

    const DOCUMENT_ACTION_INTENDED_INPUT_DB = "I";
    const DOCUMENT_ACTION_INTENDED_OUTPUT_DB = "O";

    const DOCUMENT_ACTION_INTENDED_INPUT = "Input";
    const DOCUMENT_ACTION_INTENDED_OUTPUT = "Output";

    const DOCUMENT_APPLY_SUPPLIER_DB = "S";
    const DOCUMENT_APPLY_CUSTOMER_DB = "C";

    const DOCUMENT_APPLY_SUPPLIER = "Supplier";
    const DOCUMENT_APPLY_CUSTOMER = "Customer";

    const OPTION_YES_DB = "Y";
    const OPTION_NO_DB = "N";

    const OPTION_YES = "Yes";
    const OPTION_NO = "No";

    const OPTION_ALL = "All";

    const SELECTED_COMPANY_ID = 'selected_company_id';

    const MAX_LENGTH_NUM_TRANSACTION = 10;

    const MESSAGE_PAGE_NOT_EXISTS = 'The requested page does not exist.';
    const MESSAGE_INCORRECT_LOGIN = 'Incorrect email or password.';
    const MESSAGE_PASSWORD_VALIDATION = 'New password must contain at least one lower and upper case character and a digit.';
    const MESSAGE_PASSWORDS_NOT_MATCH = "Passwords don't match";
    const MESSAGE_VALIDATE_PHONE = 'Please provide digits only no more than 13.';

    const MESSAGE_NON_ACTIVE_USER = "You cannot perform this action while you appear as a non-active user.";
    const MESSAGE_NOT_ENOUGH_PERMISSIONS = "You do not have permission to visit this page.";

    const MESSAGE_OWNER_NOT_DEMOTE = "The owner of a company cannot be demoted.";
    const MESSAGE_CANNOT_PROMOTE_INACTIVE = "You cannot promote/demote inactive users.";
    const MESSAGE_CANNOT_ACTIVATE_YOURSELF = "You cannot activate/deactivate yourself.";
    const MESSAGE_OWNER_NOT_ACTIVATE = "The owner of a company cannot be activated/deactivated.";
    const MESSAGE_CANNOT_ACTIVATE_SUPERVISORS = "You cannot activate/deactivate other supervisors.";
    const MESSAGE_NOT_CREATE_NEW_APPLICATION_EXISTING_PENDING = "You cannot create a new application for this company while you have another pending one.";
    const MESSAGE_COMMENT_USER = 'You should enter a comment for the company.';
    const MESSAGE_COMMENT_COMPANY = 'You should enter a feedback for the applicant.';

    const MESSAGE_INFO_NOT_BELONG_COMPANY = "That information does not belong to your selected company.";
    const MESSAGE_INFO_DELETED_NOT_DRAFT_TRANSACTION = "You can only delete a draft transaction.";

}
