<?php

declare(strict_types=1);

namespace Be\Pattern\LoanApplication\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

#[Message([
    'en' => 'Invalid applicant ID. Please use the format APP-XXXX.',
    'ja' => '無効な申請者IDです。APP-XXXXの形式で入力してください。'
])]
final class InvalidApplicantIdException extends DomainException
{
}
