<?php

class Core2_Rsa
{
	/**
	 *  @var string
	 */
	protected $_privateKey = '-----BEGIN PRIVATE KEY-----
MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBAN9DlPwXzVLVVOwf
pORfUk6NeZdty3OVFNaBb0hOQvKFShYtZ2OOkcYXBHO9kIQVDaw/QilHaf63NJrs
xZTvutUQ1u01XLqbOPtUxSptUU3x328gtE/Hq3ALQw9Kcj9Jl2AYbi1+qD1EqFuT
1dC0UBfQr0coRVB9cl2p0QQXEE3XAgMBAAECgYATlAaVkLTFpcV7q063ZBCAqPFX
sR4dNZ6NLO6IRayjPcMAbNzbIx5vY4dVavMoUfxP9YAkxIlr977aMxckd11HtfNJ
pq6FTwN7STp4B6A8VDe2PUXZNDX0GZrR9mGOyTUZMATFfSu877Bw6AOyyn1g5Ui1
X2T4XNvkW2Cgs7PYoQJBAO/jEhWff6g9+QxzMm8D8g64Av5gUeomYuZ1WSOfon6k
JBvJr+j5qS1Se2FPQPi34uDG4GN9QrjVz9dJcYj5AycCQQDuQqx8m5lnvgN0Hmsu
tfPaxxiGGt0fQtf6Cz9XWoMC/iy/TlFmDaH4XCFNRS2XgW1Ymyz9P80vdq5fbRCO
3k3RAkEAkl92qHUDWvA9p+gerPi2WV7UjMzPDtXPYRnXg1Ijv6x+T+pYCQtVvE7o
8+59EYZ6zHbtcid7b/ce9BlfSpnO3wJBANMAWu7zgbS9MyPHuJYibzYF8fL5oXAI
62o2Qb8jmjixToGRY0bktddUB+39YLX22haJPht9QEJTcXNzDCHqP7ECQCq/sRp8
/UqJ/M5k1oCbOYx+U0nVv2uFyIi7nnbOzh/qSw+ZX/VTGsuoYDYXcz/N6ii6YGeh
Crp/4yJL6DBV9tU=
-----END PRIVATE KEY-----';
	
	/**
	 *  @var  string
	 */
	protected $_publicKey = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDfQ5T8F81S1VTsH6TkX1JOjXmX
bctzlRTWgW9ITkLyhUoWLWdjjpHGFwRzvZCEFQ2sP0IpR2n+tzSa7MWU77rVENbt
NVy6mzj7VMUqbVFN8d9vILRPx6twC0MPSnI/SZdgGG4tfqg9RKhbk9XQtFAX0K9H
KEVQfXJdqdEEFxBN1wIDAQAB
-----END PUBLIC KEY-----';
	
	/**
	 *  加密
	 * 
	 *  @param string $data 参数
	 *  @return string
	 */
	public function encode($data)
	{
		$private = openssl_pkey_get_private($this->_privateKey);
		openssl_private_encrypt($data,$encrypted,$private);
		
		return base64_encode($encrypted);
	}
	
	/**
	 *  解密
	 * 
	 *  @param string $encrypted 加密参数
	 *  @return string
	 */
	public function decode($encrypted) 
	{
		$private = openssl_pkey_get_private($this->_privateKey);
		openssl_private_decrypt(base64_decode($encrypted),$decrypted,$private);
		
		return $decrypted;
	}
}

?>