<?php

namespace AddressBookBundle\DataFixtures\ORM;

use AddressBookBundle\Entity\Contact;
use AddressBookBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadContactData implements FixtureInterface, ContainerAwareInterface {

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('seeder');
        $user->setEmail('seeder@seed.com');
        $encoder = $this->container->get('security.password_encoder');
        $password = $encoder->encodePassword($user, '1234');
        $user->setPassword($password);
        $manager->persist($user);
        $userId = $user->getId();
        $manager->flush();

        for ($i = 0; $i < 50; $i++) {
            $contact = new Contact();
            $contact->setFirstname($this->fakeFirstname());
            $contact->setLastname($this->fakeLastname());
            $contact->setAddress($this->fakeStreet() . ', ' . rand ( 10, 999));
            $contact->setZip(rand ( 10101 , 89000 ));
            $contact->setCity($this->fakeCity());
            $contact->setCountry($this->fakeCountry());
            $contact->setPhonenumber('160'.rand (  1000, 9999).rand (  1000, 99999));
            $date = new \DateTime();
            $date->modify('-' . rand(30, 70) . ' day');
            $contact->setBirthday($date);
            $contact->setEmail(
                strtolower($contact->getFirstname() . rand ( 10101 , 89000 ) . '@example.com')
            );
            $contact->setUser($user);
            $manager->persist($contact);
        }
        $manager->flush();
    }

    /**
     * @inheritDoc
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @return mixed
     */
    private function fakeFirstname(){
        $array = ['Lucas', 'Ursula', 'Jürgen', 'Christina', 'Karl', 'Ilse', 'Stefan', 'Ingrid', 'Walter', 'Petra', 'Uwe', 'Monika', 'Hans', 'Susanne', 'Klaus', 'Anna', 'Günter', 'Emma'];
        return $array[rand ( 0 , count($array) -1)];
    }

    /**
     * @return mixed
     */
    private function fakeLastname(){
        $array = ['Müller', 'Schmidt', 'Schneider', 'Fischer', 'Weber', 'Meyer', 'Wagner', 'Becker', 'Schulz', 'Hoffmann', 'Bauer', 'Richter', 'Klein', 'Johannis', 'Neumann', 'Zimmermann'];
        return $array[rand ( 0 , count($array) -1)];
    }

    /**
     * @return mixed
     */
    private function fakeStreet(){
        $array = ['Deichstraße', 'Freßgass', 'Hohe Straße', 'Kaiserhofstraße', 'Kirchgasse', 'Schildergasse', 'Goethe Platz', 'Zeil'];
        return $array[rand ( 0 , count($array) -1)];
    }

    /**
     * @return mixed
     */
    private function fakeCity(){
        $array = ['Frankfurt am Main', 'Wiesbaden', 'Darmstadt', 'Schneidhein', 'Königstein im Taunus', 'Kronberg', 'Kasel', 'Offenbach am Main'];
        return $array[rand ( 0 , count($array) -1)];
    }

    /**
     * @return mixed
     */
    private function fakeCountry(){
        $array = ['RO', 'DE', 'FR', 'UK', 'DK', 'US', 'NL', 'IT'];
        return $array[rand ( 0 , count($array) -1)];
    }
}