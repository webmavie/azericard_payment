<?php
class H_azericard
{
	private $amount;
	private $currency = 944;
	private $orderId;
	private $description;
	private $merchantName;
	private $merchantUrl;
	private $terminal;
	private $email;
	private $trtype = 1;
	private $merchGMT = '+4';
	private $backref;
	private $url = 'https://mpi.3dsecure.az/cgi-bin/cgi_link';
	private $country = 'AZ';
	private $keyForSign;

	private $htmlForm;

	private $timestamp;
	private $nonce;

	public function __construct($terminal, $merchantName) {
		$this->terminal = $terminal;
		$this->merchantName = $merchantName;
	}

	public function setAmount($amount) {
		$this->amount = $amount;
	}

	public function setCurrency($currency) {
		$this->currency = $currency;
	}

	public function setOrderId($orderId) {
		$this->orderId = str_pad($orderId, 6, '0', STR_PAD_LEFT);
	}

	public function setDescription($description) {
		$this->description = $description;
	}

	public function setMerchantUrl($merchantUrl) {
		$this->merchantUrl = $merchantUrl;
	}

	public function setEmail($email) {
		$this->email = $email;
	}

	public function setTrtype($trtype) {
		$this->trtype = $trtype;
	}

	public function setMerchGMT($merchGMT) {
		$this->merchGMT = $merchGMT;
	}

	public function setBackref($backref) {
		$this->backref = $backref;
	}

	public function setUrl($url) {
		$this->url = $url;
	}

	public function setCountry($country) {
		$this->country = $country;
	}

	public function setKeyForSign($keyForSign) {
		$this->keyForSign = $keyForSign;
	}

	private function generateTimestamp() {
		return gmdate('YmdHis');
	}

	private function generateNonce() {
		return substr(md5(rand()), 0, 16);
	}

	private function hex2bin2($hexdata) {
		$bindata = "";
		for ($i = 0; $i < strlen($hexdata); $i += 2) {
			$bindata .= chr(hexdec(substr($hexdata, $i, 2)));
		}
		return $bindata;
	}

	private function generateHashUsing($timestamp, $nonce) {
		$to_sign = strlen($this->amount) . $this->amount
			. strlen($this->currency) . $this->currency
			. strlen($this->orderId) . $this->orderId
			. strlen($this->description) . $this->description
			. strlen($this->merchantName) . $this->merchantName
			. strlen($this->merchantUrl) . $this->merchantUrl
			. strlen($this->terminal) . $this->terminal
			. strlen($this->email) . $this->email
			. strlen($this->trtype) . $this->trtype
			. strlen($this->country) . $this->country
			. strlen($this->merchGMT) . $this->merchGMT
			. strlen($timestamp) . $timestamp
			. strlen($nonce) . $nonce
			. strlen($this->backref) . $this->backref;

		return hash_hmac('sha1', $to_sign, $this->hex2bin2($this->keyForSign));
	}

	public function makeForm($hideButton = FALSE, $buttonLabel = 'Pay Now', $formId = 'azericard_pay_form', $buttonId = 'azericard_pay_button') {
		$this->timestamp = $this->generateTimestamp();
		$this->nonce = $this->generateNonce();
		$pSign = $this->generateHashUsing($this->timestamp, $this->nonce);

		$form = '<form id="' . $formId . '" method="POST" action="' . $this->url . '">';
		$form .= '<input type="hidden" name="AMOUNT" value="' . $this->amount . '">';
		$form .= '<input type="hidden" name="CURRENCY" value="' . $this->currency . '">';
		$form .= '<input type="hidden" name="ORDER" value="' . $this->orderId . '">';
		$form .= '<input type="hidden" name="DESC" value="' . $this->description . '">';
		$form .= '<input type="hidden" name="MERCH_NAME" value="' . $this->merchantName . '">';
		$form .= '<input type="hidden" name="MERCH_URL" value="' . $this->merchantUrl . '">';
		$form .= '<input type="hidden" name="TERMINAL" value="' . $this->terminal . '">';
		$form .= '<input type="hidden" name="EMAIL" value="' . $this->email . '">';
		$form .= '<input type="hidden" name="TRTYPE" value="' . $this->trtype . '">';
		$form .= '<input type="hidden" name="COUNTRY" value="' . $this->country . '">';
		$form .= '<input type="hidden" name="MERCH_GMT" value="' . $this->merchGMT . '">';
		$form .= '<input type="hidden" name="BACKREF" value="' . $this->backref . '">';
		$form .= '<input type="hidden" name="TIMESTAMP" value="' . $this->timestamp . '">';
		$form .= '<input type="hidden" name="NONCE" value="' . $this->nonce . '">';
		$form .= '<input type="hidden" name="P_SIGN" value="' . $pSign . '">';
		$form .= '<button type="submit" '.($hideButton ? 'hidden' : '').' id="' . $buttonId . '" class="btn btn-primary">' . $buttonLabel . '</button>';
		$form .= '</form>';

		$this->htmlForm = $form;
	}

	public function getForm() {
		return $this->htmlForm;
	}

	public function getJsForm() {
		$this->makeForm(TRUE, '', 'azericard_pay_form', 'azericard_pay_button');
		echo $this->htmlForm;
		echo '<script>
			setTimeout(function() {
				document.getElementById("azericard_pay_form").submit();
			}, 1000);
		</script>';
	}
}
