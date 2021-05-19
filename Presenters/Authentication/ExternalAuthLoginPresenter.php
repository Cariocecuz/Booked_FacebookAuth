<?php

use Google\Service\Resource;

require_once(ROOT_DIR . 'Presenters/Authentication/LoginRedirector.php');

class ExternalAuthLoginPresenter
{
    /**
     * @var ExternalAuthLoginPage
     */
    private $page;
    /**
     * @var IWebAuthentication
     */
    private $authentication;
    /**
     * @var IRegistration
     */
    private $registration;

    public function __construct(ExternalAuthLoginPage $page, IWebAuthentication $authentication, IRegistration $registration)
    {
        $this->page = $page;
        $this->authentication = $authentication;
        $this->registration = $registration;
    }

    public function PageLoad()
    {
        if ($this->page->GetType() == 'google') {
            $email      = $_SESSION['email'];
            $firstName  = $_SESSION['givenName'];
            $lastName   = $_SESSION['familyName'];
            $this->ProcessSocialSingleSignOn($email,$firstName,$lastName);
        }
        if ($this->page->GetType() == 'fb') {
            $email      = $_SESSION['fb_email'];
            $firstName  = $_SESSION['fb_first_name'];
            $lastName   = $_SESSION['fb_last_name'];
            $this->ProcessSocialSingleSignOn($email,$firstName,$lastName);
        }
    }

    private function ProcessSocialSingleSignOn($email,$firstName,$lastName)
    {
        $code = $_GET['code'];
        Log::Debug('Logging in with social. Code=%s', $code);
    
        $requiredDomainValidator = new RequiredEmailDomainValidator($email);
        $requiredDomainValidator->Validate();
        if (!$requiredDomainValidator->IsValid()) {
            Log::Debug('Social login with invalid domain. %s', $email);
            $this->page->ShowError(array(Resources::GetInstance()->GetString('InvalidEmailDomain')));
            return;
        }

        Log::Debug('Social login successful. Email=%s', $email);
        $this->registration->Synchronize(new AuthenticatedUser($email,
            $email,
            $firstName,
            $lastName,
            Password::GenerateRandom(),
            Resources::GetInstance()->CurrentLanguage,
            Configuration::Instance()->GetDefaultTimezone(),
            null,
            null,
            null),
            false,
            false);

        $this->authentication->Login($email, new WebLoginContext(new LoginData()));
        LoginRedirector::Redirect($this->page);
    }
}
