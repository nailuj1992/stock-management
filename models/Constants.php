<?php

namespace app\models;

/**
 * Description of Constants
 *
 * @author Avuunita
 */
class Constants
{
    const EN = "en";
    const ES = "es";
    const EN_US = "en-US";
    const ES_CO = "es-CO";

    const STATUS_ACTIVE_DB = "A";
    const STATUS_INACTIVE_DB = "I";
    const STATUS_NULL_DB = "N";
    const STATUS_DRAFT_DB = "B";
    const STATUS_DELETED_DB = "D";

    const STATUS_APPROVED_DB = "A";
    const STATUS_REJECTED_DB = "R";
    const STATUS_PENDING_DB = "P";

    const ROLE_ADMIN = "admin";
    const ROLE_USER = "user";

    const ROLE_OWNER_DB = "O";
    const ROLE_SUPERVISOR_DB = "S";
    const ROLE_MEMBER_DB = "M";

    const DEFAULT_USER_CREATE = 1;

    const DOCUMENT_ACTION_INTENDED_INPUT_DB = "I";
    const DOCUMENT_ACTION_INTENDED_OUTPUT_DB = "O";

    const DOCUMENT_APPLY_SUPPLIER_DB = "S";
    const DOCUMENT_APPLY_CUSTOMER_DB = "C";

    const OPTION_ALL_SELECT = "all";

    const OPTION_YES_DB = "Y";
    const OPTION_NO_DB = "N";

    const SELECTED_COMPANY_ID = 'selected_company_id';

    const MAX_LENGTH_NUM_TRANSACTION = 10;

    const MINUS = '-';
    const NUM = '#';
    const NA = "N/A";

}
