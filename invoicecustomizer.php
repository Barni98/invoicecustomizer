<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class InvoiceCustomizer extends Module
{
    public function __construct()
    {
        $this->name = 'invoicecustomizer';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Your Name';
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Invoice Customizer');
        $this->description = $this->l('Customize the invoice font family, size, and color.');
        $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        return parent::install() && $this->registerHook('actionPDFInvoiceRender');
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    public function getContent()
    {
        return $this->renderForm();
    }

    protected function renderForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Invoice Customization Settings'),
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Font Family'),
                        'name' => 'INVOICE_FONT_FAMILY',
                        'size' => 20,
                        'required' => true,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Font Size'),
                        'name' => 'INVOICE_FONT_SIZE',
                        'size' => 5,
                        'required' => true,
                    ),
                    array(
                        'type' => 'color',
                        'label' => $this->l('Font Color'),
                        'name' => 'INVOICE_FONT_COLOR',
                        'required' => true,
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );

        $helper = new HelperForm();
        $helper->fields_value = array(
            'INVOICE_FONT_FAMILY' => Configuration::get('INVOICE_FONT_FAMILY'),
            'INVOICE_FONT_SIZE' => Configuration::get('INVOICE_FONT_SIZE'),
            'INVOICE_FONT_COLOR' => Configuration::get('INVOICE_FONT_COLOR'),
        );
        return $helper->generateForm(array($fields_form));
    }

    public function hookActionPDFInvoiceRender($params)
    {
        $pdf = $params['pdf'];
        $pdf->SetFont(Configuration::get('INVOICE_FONT_FAMILY'), '', Configuration::get('INVOICE_FONT_SIZE'));
        $pdf->SetTextColor(hexdec(substr(Configuration::get('INVOICE_FONT_COLOR'), 1, 2)), hexdec(substr(Configuration::get('INVOICE_FONT_COLOR'), 3, 2)), hexdec(substr(Configuration::get('INVOICE_FONT_COLOR'), 5, 2)));
    }
}
