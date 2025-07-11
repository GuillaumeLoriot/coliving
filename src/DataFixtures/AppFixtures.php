<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Announcement;
use App\Entity\Equipment;
use App\Entity\Service;
use App\Entity\Image;
use App\Entity\Reservation;
use App\Entity\Famouslocation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use DateTimeImmutable;
use Faker\Factory;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // ===== FAMOUS LOCATIONS =====
        $famousCities = [
            'Paris',
            'Barcelone',
            'Rome',
            'Amsterdam',
            'Lisbonne',
            'Berlin',
            'New York',
            'Tokyo',
            'Montréal',
            'Istanbul'
        ];

        $famousLocations = [];
        foreach ($famousCities as $city) {
            $location = new Famouslocation();
            $location->setCity($city);
            $manager->persist($location);
            $famousLocations[] = $location;
        }

        // ===== EQUIPMENTS =====
        $equipmentData = [
            ['title' => 'WiFi haut débit', 'description' => 'Connexion internet très haut débit (fibre optique) disponible dans tout le logement'],
            ['title' => 'Climatisation', 'description' => 'Système de climatisation dans toutes les pièces principales'],
            ['title' => 'Chauffage central', 'description' => 'Chauffage au gaz avec thermostat programmable'],
            ['title' => 'Télévision écran plat', 'description' => 'Smart TV 55 pouces avec accès aux plateformes de streaming'],
            ['title' => 'Lave-vaisselle', 'description' => 'Lave-vaisselle intégré 12 couverts, programmes éco'],
            ['title' => 'Lave-linge', 'description' => 'Machine à laver 8kg avec programmes rapides et éco'],
            ['title' => 'Sèche-linge', 'description' => 'Sèche-linge à condensation 7kg avec capteur d\'humidité'],
            ['title' => 'Four micro-ondes', 'description' => 'Micro-ondes combiné grill 25L avec fonctions automatiques'],
            ['title' => 'Réfrigérateur-congélateur', 'description' => 'Réfrigérateur américain avec distributeur d\'eau fraîche'],
            ['title' => 'Cafetière', 'description' => 'Machine à café expresso automatique avec broyeur intégré'],
            ['title' => 'Bouilloire électrique', 'description' => 'Bouilloire sans fil 1,7L avec arrêt automatique'],
            ['title' => 'Grille-pain', 'description' => 'Grille-pain 4 tranches avec réglage de brunissage'],
            ['title' => 'Aspirateur', 'description' => 'Aspirateur sans sac avec accessoires pour tous types de sols'],
            ['title' => 'Fer à repasser', 'description' => 'Fer à repasser vapeur avec table à repasser pliante'],
            ['title' => 'Sèche-cheveux', 'description' => 'Sèche-cheveux professionnel 2000W avec diffuseur'],
            ['title' => 'Parking privé', 'description' => 'Place de parking sécurisée dans garage souterrain'],
            ['title' => 'Balcon', 'description' => 'Balcon aménagé avec mobilier de jardin et vue panoramique'],
            ['title' => 'Terrasse', 'description' => 'Grande terrasse privative avec salon de jardin et barbecue'],
            ['title' => 'Jardin privatif', 'description' => 'Jardin clôturé avec pelouse et espace détente'],
            ['title' => 'Piscine', 'description' => 'Piscine chauffée avec système de filtration automatique'],
            ['title' => 'Jacuzzi', 'description' => 'Spa 6 places avec jets de massage et éclairage LED'],
            ['title' => 'Sauna', 'description' => 'Sauna traditionnel finlandais pour 4 personnes'],
            ['title' => 'Salle de sport', 'description' => 'Équipements de fitness : tapis de course, vélos, poids'],
            ['title' => 'Cheminée', 'description' => 'Cheminée à insert avec bois fourni pour soirées cosy'],
            ['title' => 'Piano', 'description' => 'Piano droit acoustique Yamaha accordé régulièrement'],
            ['title' => 'Console de jeux', 'description' => 'PlayStation 5 avec manettes et sélection de jeux'],
            ['title' => 'Vélos', 'description' => 'Vélos de ville en libre service avec casques et antivols'],
            ['title' => 'Kayaks', 'description' => 'Kayaks bi-places avec gilets de sauvetage (accès lac/rivière)'],
            ['title' => 'Barbecue', 'description' => 'Barbecue au gaz avec plancha et ustensiles inclus'],
            ['title' => 'Lit bébé', 'description' => 'Lit bébé aux normes avec matelas et linge de lit'],
            ['title' => 'Chaise haute', 'description' => 'Chaise haute évolutive avec harnais de sécurité'],
            ['title' => 'Poussette', 'description' => 'Poussette tout-terrain pliable avec ombrelle'],
            ['title' => 'Coffre-fort', 'description' => 'Coffre-fort électronique pour objets de valeur'],
            ['title' => 'Système d\'alarme', 'description' => 'Système de sécurité avec télésurveillance 24h/24'],
            ['title' => 'Interphone vidéo', 'description' => 'Portier vidéo connecté avec accès smartphone']
        ];

        $equipments = [];
        foreach ($equipmentData as $data) {
            $equipment = new Equipment();
            $equipment->setTitle($data['title'])
                ->setDescription($data['description']);
            $manager->persist($equipment);
            $equipments[] = $equipment;
        }

        // ===== SERVICES =====
        $serviceData = [
            ['title' => 'Ménage inclus', 'description' => 'Service de ménage professionnel avant et après votre séjour'],
            ['title' => 'Linge de maison fourni', 'description' => 'Draps, serviettes et linge de toilette de qualité hôtelière'],
            ['title' => 'Accueil personnalisé', 'description' => 'Accueil sur place avec remise des clés et présentation du logement'],
            ['title' => 'Conciergerie 24h/24', 'description' => 'Service de conciergerie disponible jour et nuit pour vos demandes'],
            ['title' => 'Petit-déjeuner livré', 'description' => 'Petit-déjeuner continental livré chaque matin à domicile'],
            ['title' => 'Courses livrées', 'description' => 'Service de courses avec livraison avant votre arrivée'],
            ['title' => 'Navette aéroport', 'description' => 'Transfer privé depuis/vers l\'aéroport sur réservation'],
            ['title' => 'Location de voiture', 'description' => 'Véhicule de location disponible avec livraison sur place'],
            ['title' => 'Guide touristique', 'description' => 'Guide local privé pour visites personnalisées de la région'],
            ['title' => 'Réservation restaurants', 'description' => 'Réservation dans les meilleurs restaurants de la ville'],
            ['title' => 'Billetterie spectacles', 'description' => 'Réservation de places pour concerts, théâtres et événements'],
            ['title' => 'Massages à domicile', 'description' => 'Masseur professionnel se déplace dans le logement'],
            ['title' => 'Chef à domicile', 'description' => 'Chef cuisinier privé pour repas gastronomiques sur mesure'],
            ['title' => 'Baby-sitting', 'description' => 'Service de garde d\'enfants par professionnels agréés'],
            ['title' => 'Promenade d\'animaux', 'description' => 'Service de promenade et garde pour vos animaux domestiques'],
            ['title' => 'Laverie express', 'description' => 'Service de laverie avec récupération et livraison en 24h'],
            ['title' => 'Maintenance technique', 'description' => 'Intervention rapide pour tout problème technique'],
            ['title' => 'Check-in tardif', 'description' => 'Possibilité d\'arrivée tardive après 22h sans supplément'],
            ['title' => 'Stockage bagages', 'description' => 'Garde de bagages avant check-in ou après check-out'],
            ['title' => 'WiFi professionnel', 'description' => 'Connexion internet renforcée pour télétravail']
        ];

        $services = [];
        foreach ($serviceData as $data) {
            $service = new Service();
            $service->setTitle($data['title'])
                ->setDescription($data['description']);
            $manager->persist($service);
            $services[] = $service;
        }

        // ===== USERS =====
        $users = [];

        // Admins
        for ($i = 0; $i < 2; $i++) {
            $admin = new User();
            $admin->setEmail("admin$i@example.com")
                ->setRoles(['ROLE_ADMIN'])
                ->setPassword($this->passwordHasher->hashPassword($admin, 'password'))
                ->setName($faker->lastName())
                ->setFirstName($faker->firstName())
                ->setBillingAddress($faker->address())
                ->setIsVerified(true)
                ->setCreatedAt(new DateTimeImmutable())
                ->setBirthDate($faker->dateTimeBetween('-60 years', '-25 years'));
            $manager->persist($admin);
        }

        // Employees
        for ($i = 0; $i < 3; $i++) {
            $employee = new User();
            $employee->setEmail("employee$i@example.com")
                ->setRoles(['ROLE_EMPLOYEE'])
                ->setPassword($this->passwordHasher->hashPassword($employee, 'password'))
                ->setName($faker->lastName())
                ->setFirstName($faker->firstName())
                ->setBillingAddress($faker->address())
                ->setIsVerified(true)
                ->setCreatedAt(new DateTimeImmutable())
                ->setBirthDate($faker->dateTimeBetween('-50 years', '-22 years'));
            $manager->persist($employee);
        }

        // Owners
        $owners = [];
        for ($i = 0; $i < 8; $i++) {
            $owner = new User();
            $owner->setEmail("owner$i@example.com")
                ->setRoles(['ROLE_OWNER'])
                ->setPassword($this->passwordHasher->hashPassword($owner, 'password'))
                ->setName($faker->lastName())
                ->setFirstName($faker->firstName())
                ->setBillingAddress($faker->address())
                ->setIsVerified($faker->boolean(90)) // 90% verified
                ->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-2 years', '-1 month')))
                ->setBirthDate($faker->dateTimeBetween('-65 years', '-25 years'));
            $manager->persist($owner);
            $owners[] = $owner;
        }

        // Clients
        $clients = [];
        for ($i = 0; $i < 15; $i++) {
            $client = new User();
            $client->setEmail("client$i@example.com")
                ->setRoles(['ROLE_CLIENT'])
                ->setPassword($this->passwordHasher->hashPassword($client, 'password'))
                ->setName($faker->lastName())
                ->setFirstName($faker->firstName())
                ->setBillingAddress($faker->address())
                ->setIsVerified($faker->boolean(85)) // 85% verified
                ->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-18 months', '-1 week')))
                ->setBirthDate($faker->dateTimeBetween('-70 years', '-18 years'));
            $manager->persist($client);
            $clients[] = $client;
        }

        // ===== ANNOUNCEMENTS =====
        $announcementTitles = [
            // Appartements urbains
            'Appartement moderne centre-ville avec terrasse',
            'Studio design quartier historique',
            'Loft industriel avec mezzanine',
            'Duplex lumineux proche commerces',
            'Penthouse avec vue panoramique',
            'Appartement cosy proche métro',
            'F3 rénové dans résidence calme',
            'Grand appartement familial avec balcon',

            // Maisons
            'Maison de charme avec jardin privatif',
            'Villa moderne avec piscine chauffée',
            'Maison de campagne authentique',
            'Chalet montagnard tout confort',
            'Maison bourgeoise d\'époque rénovée',
            'Villa contemporaine vue mer',
            'Longère bretonne typique',
            'Mas provençal avec oliveraie',
            'Fermette restaurée cadre bucolique',

            // Logements spécialisés
            'Gîte rural au cœur de la nature',
            'Chambre d\'hôtes de luxe',
            'Tiny house écologique',
            'Roulotte bohème chic',
            'Cabane dans les arbres',
            'Péniche aménagée sur la Seine',
            'Château privatisable pour groupes',
            'Domaine viticole avec dégustation',

            // Logements thématiques
            'Loft d\'artiste atypique',
            'Appartement vintage années 70',
            'Suite romantique avec jacuzzi',
            'Logement familial proche Disneyland',
            'Studio étudiant tout équipé',
            'Appart-hôtel services inclus',
            'Résidence de vacances all-inclusive'
        ];

        $locationTypes = [
            'Centre-ville',
            'Quartier historique',
            'Zone commerciale',
            'Quartier résidentiel',
            'Bord de mer',
            'Montagne',
            'Campagne',
            'Périphérie',
            'Zone touristique'
        ];

        $frenchAddresses = [
    [
        'address' => '10 Rue de Rivoli',
        'city' => 'Paris',
        'zipcode' => '75004',
        'latitude' => 48.8556,
        'longitude' => 2.3608,
    ],
    [
        'address' => '20 Place Bellecour',
        'city' => 'Lyon',
        'zipcode' => '69002',
        'latitude' => 45.7578,
        'longitude' => 4.8320,
    ],
    [
        'address' => '1 La Canebière',
        'city' => 'Marseille',
        'zipcode' => '13001',
        'latitude' => 43.2965,
        'longitude' => 5.3752,
    ],
    [
        'address' => '15 Rue Alsace Lorraine',
        'city' => 'Toulouse',
        'zipcode' => '31000',
        'latitude' => 43.6043,
        'longitude' => 1.4437,
    ],
    [
        'address' => '5 Promenade des Anglais',
        'city' => 'Nice',
        'zipcode' => '06000',
        'latitude' => 43.6959,
        'longitude' => 7.2652,
    ],
    [
        'address' => '4 Rue de Strasbourg',
        'city' => 'Nantes',
        'zipcode' => '44000',
        'latitude' => 47.2173,
        'longitude' => -1.5532,
    ],
    [
        'address' => '8 Cours Pasteur',
        'city' => 'Bordeaux',
        'zipcode' => '33000',
        'latitude' => 44.8375,
        'longitude' => -0.5792,
    ],
    [
        'address' => '12 Place Kléber',
        'city' => 'Strasbourg',
        'zipcode' => '67000',
        'latitude' => 48.5839,
        'longitude' => 7.7455,
    ],
    [
        'address' => '6 Avenue Jean Médecin',
        'city' => 'Nice',
        'zipcode' => '06000',
        'latitude' => 43.7015,
        'longitude' => 7.2651,
    ],
    [
        'address' => '25 Rue Sainte-Catherine',
        'city' => 'Bordeaux',
        'zipcode' => '33000',
        'latitude' => 44.8386,
        'longitude' => -0.5790,
    ],
    [
        'address' => '3 Place du Capitole',
        'city' => 'Toulouse',
        'zipcode' => '31000',
        'latitude' => 43.6045,
        'longitude' => 1.4440,
    ],
    [
        'address' => '9 Boulevard Haussmann',
        'city' => 'Paris',
        'zipcode' => '75009',
        'latitude' => 48.8738,
        'longitude' => 2.3320,
    ],
    [
        'address' => '22 Avenue Foch',
        'city' => 'Lyon',
        'zipcode' => '69006',
        'latitude' => 45.7680,
        'longitude' => 4.8497,
    ],
    [
        'address' => '13 Rue de la République',
        'city' => 'Marseille',
        'zipcode' => '13001',
        'latitude' => 43.2968,
        'longitude' => 5.3743,
    ],
    [
        'address' => '2 Rue des Halles',
        'city' => 'Paris',
        'zipcode' => '75001',
        'latitude' => 48.8610,
        'longitude' => 2.3469,
    ],
    [
        'address' => '17 Rue Crébillon',
        'city' => 'Nantes',
        'zipcode' => '44000',
        'latitude' => 47.2179,
        'longitude' => -1.5562,
    ],
    [
        'address' => '11 Rue du Vieux Marché aux Poissons',
        'city' => 'Strasbourg',
        'zipcode' => '67000',
        'latitude' => 48.5820,
        'longitude' => 7.7482,
    ],
    [
        'address' => '33 Rue Esquermoise',
        'city' => 'Lille',
        'zipcode' => '59800',
        'latitude' => 50.6371,
        'longitude' => 3.0635,
    ],
    [
        'address' => '7 Place du Ralliement',
        'city' => 'Angers',
        'zipcode' => '49100',
        'latitude' => 47.4722,
        'longitude' => -0.5552,
    ],
    [
        'address' => '9 Rue Léon Gambetta',
        'city' => 'Lille',
        'zipcode' => '59000',
        'latitude' => 50.6318,
        'longitude' => 3.0575,
    ],
];

  $announcements = [];
        foreach ($owners as $owner) {
            $count = rand(2, 4);
            for ($i = 0; $i < $count; $i++) {
$loc = $frenchAddresses[array_rand($frenchAddresses)];

                $ann = new Announcement();
                $title = $faker->randomElement($announcementTitles);
                $locationType = $faker->randomElement($locationTypes);

                // Description réaliste selon le type de logement
                $descriptions = [
                    "Magnifique logement situé en $locationType, parfait pour un séjour de détente. Récemment rénové avec goût, il allie confort moderne et charme authentique. L'espace de vie lumineux et spacieux offre une atmosphère chaleureuse. La cuisine entièrement équipée permet de préparer vos repas en toute convenance.",

                    "Découvrez ce superbe hébergement en $locationType, idéal pour explorer la région. Le logement dispose de tous les équipements nécessaires pour un séjour réussi. L'aménagement soigné et la décoration raffinée créent une ambiance unique. Profitez des espaces extérieurs pour vous détendre après vos visites.",

                    "Séjournez dans ce charmant logement en $locationType, conçu pour votre bien-être. Les pièces spacieuses et lumineuses offrent un cadre de vie agréable. La literie de qualité garantit des nuits reposantes. L'emplacement privilégié vous permet d'accéder facilement aux attractions locales.",

                    "Vivez une expérience unique dans ce logement d'exception en $locationType. Chaque détail a été pensé pour votre confort et votre plaisir. Les équipements haut de gamme et les services inclus font de ce lieu un véritable cocon. L'environnement paisible est parfait pour se ressourcer.",
                ];

                // Image de couverture selon le type de logement
                $coverImageUrl = $this->getCoverImage($title);

                $ann->setOwner($owner)
                    ->setTitle($title)
                    ->setDescription($faker->randomElement($descriptions))
                       ->setAddress($loc['address'])
    ->setCity($loc['city'])
    ->setZipcode($loc['zipcode'])
    ->setLattitude($loc['latitude'])
    ->setLongitude($loc['longitude'])
                    ->setDailyPrice($faker->randomFloat(2, 10, 100))
                    ->setMaxClient(rand(1, 8))
                    ->setImageCover($coverImageUrl);

                // Images détaillées selon le type de logement
                $imageUrls = $this->getAnnouncementImages($title);
                foreach ($imageUrls as $imageUrl) {
                    $img = new Image();
                    $img->setAnnouncement($ann)
                        ->setImageUrl($imageUrl);
                    $manager->persist($img);
                }

                // Équipements réalistes selon le type de logement
                $nbEquipments = rand(8, 15);
                $selectedEquipments = $faker->randomElements($equipments, $nbEquipments);
                foreach ($selectedEquipments as $eq) {
                    $ann->addEquipment($eq);
                }

                // Services réalistes (moins nombreux que les équipements)
                $nbServices = rand(3, 8);
                $selectedServices = $faker->randomElements($services, $nbServices);
                foreach ($selectedServices as $srv) {
                    $ann->addService($srv);
                }

                $manager->persist($ann);
                $announcements[] = $ann;
            }
        }

        // ===== RESERVATIONS =====
        $reservationStatuses = ['pending', 'accepted', 'rejected', 'cancelled', 'completed'];

        foreach ($clients as $client) {
            // Nombre variable de réservations par client
            $nbReservations = $faker->numberBetween(0, 4);

            for ($i = 0; $i < $nbReservations; $i++) {
                $announcement = $faker->randomElement($announcements);

                // Dates réalistes
                $start = $faker->dateTimeBetween('-6 months', '+3 months');
                $duration = rand(2, 14); // Entre 2 et 14 jours
                $end = (clone $start)->modify("+$duration days");

                // Prix réaliste basé sur la durée
                $pricePerNight = rand(50, 300);
                $totalAmount = $pricePerNight * $duration;

                // Statut logique selon la date
                $now = new \DateTime();
                if ($start < $now && $end < $now) {
                    $status = $faker->randomElement(['completed', 'cancelled']);
                } elseif ($start < $now) {
                    $status = 'accepted'; // En cours
                } else {
                    $status = $faker->randomElement(['pending', 'accepted', 'rejected']);
                }

                $res = new Reservation();
                $res->setClient($client)
                    ->setAnnouncement($announcement)
                    ->setStartDate(DateTimeImmutable::createFromMutable($start))
                    ->setEndDate(DateTimeImmutable::createFromMutable($end))
                    ->setStatus($status)
                    ->setTotalAmount($totalAmount)
                    ->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-6 months', 'now')));

                $manager->persist($res);
                // Créneaux d'indisponibilité
                $nbSlots = rand(1, 3);
                $slotReasons = [
                    "Vacances du propriétaire",
                    "Entretien du logement",
                    "Nettoyage approfondi",
                    "Inspection technique",
                    "Période de repos sans réservation",
                    "Indisponibilité temporaire",
                    "Fermeture annuelle",
                    "Travaux de rénovation",
                    "Maintenance de la piscine",
                    "Travaux de peinture intérieure"
                ];
                for ($j = 0; $j < $nbSlots; $j++) {
                    $slotStart = (clone $end)->modify('+' . rand(1, 10) . ' days')->setTime(rand(8, 14), 0);
                    $slotEnd = (clone $slotStart)->modify('+' . rand(2, 6) . ' hours');

                    $slot = new \App\Entity\UnavailableTimeSlot();
                    $slot->setStartDate($slotStart)
                        ->setEndDate($slotEnd)
                        ->setDescription($faker->randomElement($slotReasons))
                        ->setUnavailableslots($res);

                    $manager->persist($slot);
                }
            }
        }

        $manager->flush();
    }

    private function getCoverImage(string $title): string
    {
        // Images de couverture selon le type de logement
        $imageMapping = [
            // Appartements modernes
            'Appartement moderne centre-ville avec terrasse' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=800&h=600&fit=crop',
            'Studio design quartier historique' => 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=800&h=600&fit=crop',
            'Loft industriel avec mezzanine' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800&h=600&fit=crop',
            'Duplex lumineux proche commerces' => 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800&h=600&fit=crop',
            'Penthouse avec vue panoramique' => 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=800&h=600&fit=crop',
            'Appartement cosy proche métro' => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=800&h=600&fit=crop',
            'F3 rénové dans résidence calme' => 'https://images.unsplash.com/photo-1493809842364-78817add7ffb?w=800&h=600&fit=crop',
            'Grand appartement familial avec balcon' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=800&h=600&fit=crop',

            // Maisons
            'Maison de charme avec jardin privatif' => 'https://images.unsplash.com/photo-1570129477492-45c003edd2be?w=800&h=600&fit=crop',
            'Villa moderne avec piscine chauffée' => 'https://images.unsplash.com/photo-1613490493576-7fde63acd811?w=800&h=600&fit=crop',
            'Maison de campagne authentique' => 'https://images.unsplash.com/photo-1449824913935-59a10b8d2000?w=800&h=600&fit=crop',
            'Chalet montagnard tout confort' => 'https://images.unsplash.com/photo-1520637836862-4d197d17c783?w=800&h=600&fit=crop',
            'Maison bourgeoise d\'époque rénovée' => 'https://images.unsplash.com/photo-1598300042247-d088f8ab3a91?w=800&h=600&fit=crop',
            'Villa contemporaine vue mer' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800&h=600&fit=crop',
            'Longère bretonne typique' => 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?w=800&h=600&fit=crop',
            'Mas provençal avec oliveraie' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop',
            'Fermette restaurée cadre bucolique' => 'https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?w=800&h=600&fit=crop',

            // Logements spécialisés
            'Gîte rural au cœur de la nature' => 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=800&h=600&fit=crop',
            'Chambre d\'hôtes de luxe' => 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=800&h=600&fit=crop',
            'Tiny house écologique' => 'https://images.unsplash.com/photo-1571863533956-01c88e79957e?w=800&h=600&fit=crop',
            'Roulotte bohème chic' => 'https://images.unsplash.com/photo-1544718027-5f87e7febc78?w=800&h=600&fit=crop',
            'Cabane dans les arbres' => 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=800&h=600&fit=crop',
            'Péniche aménagée sur la Seine' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=800&h=600&fit=crop',
            'Château privatisable pour groupes' => 'https://images.unsplash.com/photo-1520637836862-4d197d17c783?w=800&h=600&fit=crop',
            'Domaine viticole avec dégustation' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop',

            // Logements thématiques
            'Loft d\'artiste atypique' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800&h=600&fit=crop',
            'Appartement vintage années 70' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800&h=600&fit=crop',
            'Suite romantique avec jacuzzi' => 'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=800&h=600&fit=crop',
            'Logement familial proche Disneyland' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=800&h=600&fit=crop',
            'Studio étudiant tout équipé' => 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=800&h=600&fit=crop',
            'Appart-hôtel services inclus' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=800&h=600&fit=crop',
            'Résidence de vacances all-inclusive' => 'https://images.unsplash.com/photo-1613490493576-7fde63acd811?w=800&h=600&fit=crop',
        ];

        return $imageMapping[$title] ?? 'https://images.unsplash.com/photo-1570129477492-45c003edd2be?w=800&h=600&fit=crop';
    }

    private function getAnnouncementImages(string $title): array
    {
        // Images détaillées selon le type de logement
        $imageMapping = [
            // Appartements modernes
            'Appartement moderne centre-ville avec terrasse' => [
                'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=1200&h=800&fit=crop', // Salon moderne
                'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=1200&h=800&fit=crop', // Cuisine équipée
                'https://images.unsplash.com/photo-1540518614846-7eded1e30f50?w=1200&h=800&fit=crop', // Chambre
                'https://images.unsplash.com/photo-1571055107559-3e67626fa8be?w=1200&h=800&fit=crop', // Terrasse
                'https://images.unsplash.com/photo-1507652313519-d4e9174996dd?w=1200&h=800&fit=crop', // Salle de bain
            ],

            'Studio design quartier historique' => [
                'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=1200&h=800&fit=crop', // Espace ouvert
                'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=1200&h=800&fit=crop', // Cuisine intégrée
                'https://images.unsplash.com/photo-1540518614846-7eded1e30f50?w=1200&h=800&fit=crop', // Coin nuit
                'https://images.unsplash.com/photo-1507652313519-d4e9174996dd?w=1200&h=800&fit=crop', // Salle de bain
            ],

            'Villa moderne avec piscine chauffée' => [
                'https://images.unsplash.com/photo-1613490493576-7fde63acd811?w=1200&h=800&fit=crop', // Extérieur villa
                'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=1200&h=800&fit=crop', // Salon spacieux
                'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=1200&h=800&fit=crop', // Cuisine moderne
                'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=1200&h=800&fit=crop', // Piscine
                'https://images.unsplash.com/photo-1540518614846-7eded1e30f50?w=1200&h=800&fit=crop', // Chambre principale
                'https://images.unsplash.com/photo-1571055107559-3e67626fa8be?w=1200&h=800&fit=crop', // Jardin
            ],

            'Maison de campagne authentique' => [
                'https://images.unsplash.com/photo-1449824913935-59a10b8d2000?w=1200&h=800&fit=crop', // Extérieur maison
                'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=1200&h=800&fit=crop', // Salon rustique
                'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=1200&h=800&fit=crop', // Cuisine campagne
                'https://images.unsplash.com/photo-1540518614846-7eded1e30f50?w=1200&h=800&fit=crop', // Chambre cosy
                'https://images.unsplash.com/photo-1571055107559-3e67626fa8be?w=1200&h=800&fit=crop', // Jardin nature
            ],

            'Chalet montagnard tout confort' => [
                'https://images.unsplash.com/photo-1520637836862-4d197d17c783?w=1200&h=800&fit=crop', // Chalet extérieur
                'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=1200&h=800&fit=crop', // Salon cheminée
                'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=1200&h=800&fit=crop', // Cuisine bois
                'https://images.unsplash.com/photo-1540518614846-7eded1e30f50?w=1200&h=800&fit=crop', // Chambre montagne
                'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=1200&h=800&fit=crop', // Vue montagne
            ],

            'Cabane dans les arbres' => [
                'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=1200&h=800&fit=crop', // Cabane extérieur
                'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=1200&h=800&fit=crop', // Intérieur cabane
                'https://images.unsplash.com/photo-1540518614846-7eded1e30f50?w=1200&h=800&fit=crop', // Chambre nature
                'https://images.unsplash.com/photo-1571055107559-3e67626fa8be?w=1200&h=800&fit=crop', // Vue forêt
            ],

            // Images par défaut pour les autres types
            'default' => [
                'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=1200&h=800&fit=crop',
                'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=1200&h=800&fit=crop',
                'https://images.unsplash.com/photo-1540518614846-7eded1e30f50?w=1200&h=800&fit=crop',
                'https://images.unsplash.com/photo-1507652313519-d4e9174996dd?w=1200&h=800&fit=crop',
            ]
        ];

        return $imageMapping[$title] ?? $imageMapping['default'];
    }
}
