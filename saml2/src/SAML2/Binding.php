<?php

/**
 * Base class for SAML 2 bindings.
 *
 * @package SimpleSAMLphp
 */
abstract class SAML2_Binding
{
    /**
     * The destination of messages.
     *
     * This can be NULL, in which case the destination in the message is used.
     */
    protected $destination;

    /**
     * Retrieve a binding with the given URN.
     *
     * Will throw an exception if it is unable to locate the binding.
     *
     * @param  string        $urn The URN of the binding.
     * @return SAML2_Binding The binding.
     * @throws Exception
     */
    public static function getBinding($urn)
    {
        assert('is_string($urn)');

        switch ($urn) {
            case SAML2_Const::BINDING_HTTP_POST:
                return new SAML2_HTTPPost();
            case SAML2_Const::BINDING_HTTP_REDIRECT:
                return new SAML2_HTTPRedirect();
            case SAML2_Const::BINDING_HTTP_ARTIFACT:
                return new SAML2_HTTPArtifact();
            case SAML2_Const::BINDING_HOK_SSO:
                return new SAML2_HTTPPost();
            default:
                throw new Exception('Unsupported binding: ' . var_export($urn, TRUE));
        }
    }

    /**
     * Guess the current binding.
     *
     * This function guesses the current binding and creates an instance
     * of SAML2_Binding matching that binding.
     *
     * An exception will be thrown if it is unable to guess the binding.
     *
     * @return SAML2_Binding The binding.
     * @throws Exception
     */
    public static function getCurrentBinding()
    {
        switch (SAML2_Utilities_Sanitizer::sanitize($_SERVER['REQUEST_METHOD'])) {
            case 'GET':
                $get = SAML2_Utilities_Sanitizer::sanitizeArray($_GET);
                if (array_key_exists('SAMLRequest', $get) || array_key_exists('SAMLResponse', $get)) {
                    return new SAML2_HTTPRedirect();
                } elseif (array_key_exists('SAMLart', $get)) {
                    return new SAML2_HTTPArtifact();
                }
                break;

            case 'POST':
                if (isset($_SERVER['CONTENT_TYPE'])) {
                    $contentType = SAML2_Utilities_Sanitizer::sanitize($_SERVER['CONTENT_TYPE']);
                    $contentType = explode(';', $contentType);
                    $contentType = $contentType[0]; /* Remove charset. */
                } else {
                    $contentType = NULL;
                }
                $post = SAML2_Utilities_Sanitizer::sanitizeArray($_POST);
                if (array_key_exists('SAMLRequest', $post) || array_key_exists('SAMLResponse', $post)) {
                    return new SAML2_HTTPPost();
                } elseif (array_key_exists('SAMLart', $post)) {
                    return new SAML2_HTTPArtifact();
                } elseif ($contentType === 'text/xml') {
                    return new SAML2_SOAP();
                }
                break;
        }

        $logger = SAML2_Utils::getContainer()->getLogger();
        $logger->warning('Unable to find the SAML 2 binding used for this request.');
        $logger->warning('Request method: ' . var_export(SAML2_Utilities_Sanitizer::sanitize($_SERVER['REQUEST_METHOD']), TRUE));
        if (!empty($_GET)) {
            $logger->warning("GET parameters: '" . implode("', '", array_map('addslashes', array_keys(SAML2_Utilities_Sanitizer::sanitizeArray($_GET)))) . "'");
        }
        if (!empty($_POST)) {
            $logger->warning("POST parameters: '" . implode("', '", array_map('addslashes', array_keys(SAML2_Utilities_Sanitizer::sanitizeArray($_POST)))) . "'");
        }
        if (isset($_SERVER['CONTENT_TYPE'])) {
            $logger->warning('Content-Type: ' . var_export(SAML2_Utilities_Sanitizer::sanitize($_SERVER['CONTENT_TYPE']), TRUE));
        }

        throw new Exception('Unable to find the current binding.');
    }

    /**
     * Retrieve the destination of a message.
     *
     * @return string|NULL $destination  The destination the message will be delivered to.
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * Override the destination of a message.
     *
     * Set to NULL to use the destination set in the message.
     *
     * @param string|NULL $destination The destination the message should be delivered to.
     */
    public function setDestination($destination)
    {
        assert('is_string($destination) || is_null($destination)');

        $this->destination = $destination;
    }

    /**
     * Send a SAML 2 message.
     *
     * This function will send a message using the specified binding.
     * The message will be delivered to the destination set in the message.
     *
     * @param SAML2_Message $message The message which should be sent.
     */
    abstract public function send(SAML2_Message $message);

    /**
     * Receive a SAML 2 message.
     *
     * This function will extract the message from the current request.
     * An exception will be thrown if we are unable to process the message.
     *
     * @return SAML2_Message The received message.
     */
    abstract public function receive();

}
