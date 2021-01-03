<?php namespace GromIT\Tenancy\Components;

use Cms\Classes\CodeBase;
use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use InvalidArgumentException;
use GromIT\Tenancy\Classes\TenancyManager;

class CurrentTenant extends ComponentBase
{
    /**
     * @var string
     */
    private $langKey;

    /**
     * @var \GromIT\Tenancy\Classes\TenancyManager
     */
    private $tenancyManager;

    public function __construct(CodeBase $cmsObject = null, $properties = [])
    {
        parent::__construct($cmsObject, $properties);

        $this->langKey        = 'gromit.tenancy::lang.components.current_tenant';
        $this->tenancyManager = TenancyManager::instance();
    }

    public function componentDetails(): array
    {
        return [
            'name'        => "{$this->langKey}.details.name",
            'description' => "{$this->langKey}.details.description",
        ];
    }

    public function defineProperties(): array
    {
        $title       = "{$this->langKey}.properties.redirect.title";
        $description = "{$this->langKey}.properties.redirect.description";

        return [
            'redirect' => [
                'title'       => $title,
                'description' => $description,
                'type'        => 'dropdown'
            ],
        ];
    }

    public function getRedirectOptions(): array
    {
        $options = [
            '-1' => "{$this->langKey}.properties.redirect.options.no_redirect",
        ];

        /** @noinspection PhpUndefinedMethodInspection */
        $options += Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');

        return $options;
    }

    public function onRun()
    {
        $this->page['currentTenant'] = null;

        if (!$this->tenancyManager->hasCurrent()) {
            $redirect = $this->property('redirect');

            if (empty($redirect)) {
                throw new InvalidArgumentException('Redirect property is empty');
            }

            if ($redirect === '-1') {
                return null;
            }

            $redirectUrl = $this->controller->pageUrl($this->property('redirect'));

            return redirect($redirectUrl);
        }

        $this->page['currentTenant'] = $this->tenancyManager->getCurrent();

        return null;
    }
}
