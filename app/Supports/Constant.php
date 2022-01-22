<?php


namespace App\Supports;

/**
 * Class Constant
 * @package App\Supports
 */
class Constant
{
    /**
     * System Model Status
     */
    const ENABLED_OPTIONS = ['yes' => 'Yes', 'no' => 'No'];

    /**
     * System User Permission Guards
     */
    const PERMISSION_GUARDS = ['web' => 'WEB'];

    /**
     * System Permission Title Constraint
     */
    const PERMISSION_NAME_ALLOW_CHAR = '([a-zA-Z0-9.-_]+)';

    /**
     * Keyword to purge Soft Deleted Models
     */
    const PURGE_MODEL_QSA = 'purge';

    /**
     * Timing Constants
     */
    const SECOND = '1';
    const MINUTE = '60';
    const HOUR = '3600';
    const DAY = '86400';
    const WEEK = '604800';
    const MONTH = '2592000';
    const YEAR = '31536000';
    const DECADE = '315360000'; //1de=10y

    /**
     * Toastr Message Levels
     */
    const MSG_TOASTR_ERROR = 'error';
    const MSG_TOASTR_WARNING = 'warning';
    const MSG_TOASTR_SUCCESS = 'success';
    const MSG_TOASTR_INFO = 'info';

    /**
     * Authentication Login Medium
     */
    const LOGIN_EMAIL = 'email';
    const LOGIN_USERNAME = 'username';
    const LOGIN_MOBILE = 'mobile';
    const LOGIN_OTP = 'otp';

    /**
     * OTP Medium Source
     */
    const OTP_MOBILE = 'mobile';
    const OTP_EMAIL = 'email';

    const EXPORT_OPTIONS = [
        'xlsx' => 'Microsoft Excel (.xlsx)',
        'ods' => 'Open Document Spreadsheet (.ods)',
        'csv' => 'Comma Seperated Values (.csv)'
    ];

    /**
     * Default Role ID for frontend registered user
     */
    const GUEST_ROLE_ID = 7;

    /**
     * Default Sender ID for frontend registered user
     */
    const SENDER_ROLE_ID = 7;
    /**
     * Default Receiver ID for frontend registered user
     */
    const RECEIVER_ROLE_ID = 8;
    /**
     * Default Role Name for system administrator
     */
    const SUPER_ADMIN_ROLE = 'Super Administrator';

    /**
     * Default Mobile Number for backend admin panel
     */
    const MOBILE = '01710534092';

    /**
     * Default Backend Preference ID for backend admin panel
     */
    const USER_ID = 1;

    /**
     * Default Email Address for backend admin panel
     */
    const EMAIL = 'hafijul233@gmail.com';

    /**
     * Default model enabled status
     */
    const ENABLED_OPTION = 'yes';

    /**
     * Default model disabled status
     */
    const DISABLED_OPTION = 'no';

    /**
     * Default Guard for all users if any special is not provided
     */
    const PERMISSION_GUARD = 'web';
    /**
     * Default Password
     */
    const PASSWORD = 'password';

    /**
     * Default profile display image is user image is missing
     */
    const USER_PROFILE_IMAGE = '/assets/img/AdminLTELogo.png';

    /**
     * Default Export Option
     */
    const EXPORT_DEFAULT = 'xlsx';

    /**
     * Default Logged User Redirect Route
     */
    const DASHBOARD_ROUTE = 'backend.dashboard';

    const LOCALE = 'en';

    const TRUCK_LOAD_STATUS = [
        'pending' => 'Pending',
        'needs carrier' => 'Needs Carrier',
        'dispatched' => 'Dispatched',
        'in transit' => 'In Transit',
        'booked -awaiting confirmation' => '',
        'ready - confirmation signed' => '',
        'watch' => 'Watch',
        'possible claims' => '',
        'completed to be billed' => 'Completed to be billed',
        'billed' => 'Billed',
        'paid' => 'Paid',
        'actual claim' => 'Actual Claim',
        'canceled' => 'Canceled',
        'archived' => 'Archived',
    ];
}
