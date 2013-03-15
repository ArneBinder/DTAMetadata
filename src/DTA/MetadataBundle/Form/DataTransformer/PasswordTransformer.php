<?php

namespace DTA\MetadataBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use Acme\TaskBundle\Entity\Issue;

class PasswordTransformer implements DataTransformerInterface
{
    /**
     * Apply the application wide configured encryption (app/config/security.yml) to the input plaintext password.
     *
     * @param  Issue|null $issue
     * @return Hashed password
     */
    public function transform($plaintext)
    {
        $factory = $this->get('security.encoder_factory');
	$encoder = $factory->getEncoder($user);
	
        return $encoder->encodePassword($plaintext, $user->getSalt());
    }

    /**
     * Transforms a string (number) to an object (issue).
     *
     * @param  string $number
     *
     * @return Issue|null
     *
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($number)
    {
        if (!$number) {
            return null;
        }

        $issue = $this->om
            ->getRepository('AcmeTaskBundle:Issue')
            ->findOneBy(array('number' => $number))
        ;

        if (null === $issue) {
            throw new TransformationFailedException(sprintf(
                'An issue with number "%s" does not exist!',
                $number
            ));
        }

        return $issue;
    }
}
?>
