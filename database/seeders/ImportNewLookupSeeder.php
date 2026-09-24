<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\UUID;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
// use App\Support\UUID; // adjust this import based on where UUID::issue() is defined

class ImportNewLookupSeeder extends Seeder
{
    public function run(): void
    {
        $event = Event::where('slug', '1226292026')->first(); // replace with your actual event value

        $members = [
            ['Ronald', 'Ablir', null],
            ['Rafael', 'Asis', null],
            ['Karla', 'Baldorado', null],
            ['Mariza', 'Baybayon', null],
            ['Melanie', 'Berroya', null],
            ['Mark Louis', 'Birot', null],
            ['Emilyn', 'Bontilao', null],
            ['Jherbee', 'Cabanding', null],
            ['Tricia May', 'Cagaoan', null],
            ['Ayiexa', 'Camiring', null],
            ['Angela', 'Canoy', null],
            ['Jerry', 'Causaren', null],
            ['Rose Ann', 'Causaren', null],
            ['TESTEvang', 'Coordinator', null],
            ['John Louis', 'De vera Estopa', null],
            ['Kenneth', 'Dela Mata', null],
            ['Mary Jane', 'Devosora', null],
            ['Hannah', 'Dizon', null],
            ['Kris Alvin', 'Edquid', null],
            ['Jerald', 'Fernandez', null],
            ['Erwin Lloyd', 'Francisco', null],
            ['Carlo', 'Gayola', null],
            ['Jan Chloie', 'Guerrero', null],
            ['Hazel', 'Guerrero', null],
            ['Denise', 'Hael', null],
            ['Lebbie', 'Ignacio', null],
            ['John Michael', 'Jagape', null],
            ['Eula', 'Larugal', null],
            ['Emily', 'Lupangco', null],
            ['Tricia Ann', 'Magbanua', null],
            ['Tricia', 'Manansala', null],
            ['Teresita', 'Manansala', null],
            ['Jesus', 'Manansala', null],
            ['Fatricia', 'Manansala', null],
            ['Jonela', 'Matias', null],
            ['Marry Jean', 'Mayor', null],
            ['Julie Ann', 'Montialbucio', null],
            ['Joselito', 'Navarro', null],
            ['Angeliqua', 'Pabalate', null],
            ['Lovely Rose', 'Patuga', null],
            ['Maria', 'Paz', null],
            ['Marchel', 'Quanico', null],
            ['Jyssel', 'Raniola', null],
            ['Aldrin', 'Romero', null],
            ['Jowela', 'Romero', null],
            ['Gabriel Jhae', 'Ruiz', null],
            ['Pious', 'Seneres', null],
            ['Maria Jessa', 'Sia', null],
            ['Justin', 'Sia', null],
            ['Niel Kian', 'Tayam', null],
            ['Julieta', 'Tolentino', null],
            ['Angeluz', 'Villagracia', null],

            ['Rhea Jane G.', 'Ecoben', 'Angono'],
            ['Werlyn G.', 'Ecoben', 'Angono'],
            ['Rhean', 'Eludo', 'Angono'],
            ['Zenaida', 'Ignacio', 'Angono'],
            ['Eand', 'Perez', 'Angono'],
            ['Rey', 'Sedaya', 'Angono'],

            ['Jazreal', 'Balanay', 'C3C4 (Dasma/Imus/Bacoor)'],
            ['Jirah', 'Balanay', 'C3C4 (Dasma/Imus/Bacoor)'],
            ['Danialle', 'Padernal', 'C3C4 (Dasma/Imus/Bacoor)'],
            ['Emily', 'Pascual-Dasing', 'C3C4 (Dasma/Imus/Bacoor)'],

            ['Nicole', 'Sison', 'Canada'],
            ['Celia', 'Uyao', 'Canada'],

            ['Mary Ann', 'Bernas', 'CB (Carmona/Binan)'],
            ['Asteris', 'Genotiva', 'CB (Carmona/Binan)'],
            ['Guillerma', 'Genotiva', 'CB (Carmona/Binan)'],
            ['Brandale', 'Genotiva', 'CB (Carmona/Binan)'],
            ['Christian', 'Genotiva', 'CB (Carmona/Binan)'],
            ['Jose', 'Hacinas', 'CB (Carmona/Binan)'],
            ['Jovel Lyn', 'Mapalad', 'CB (Carmona/Binan)'],
            ['Kevin', 'Santos', 'CB (Carmona/Binan)'],
            ['Mario', 'Soliveres', 'CB (Carmona/Binan)'],
            ['Felicia', 'Soliveres', 'CB (Carmona/Binan)'],
            ['Janna', 'Tupas', 'CB (Carmona/Binan)'],
            ['Marilou', 'Billota', 'CB (Carmona/Binan)'],

            ['Noeige', 'Cusito', 'CLB Calamba'],
            ['Karla', 'Maraya', 'CLB Calamba'],
            ['Pablo James', 'Reyes', 'CLB Calamba'],

            ['Hero John', 'Arevalo', 'LP (Las Pinas)'],
            ['Keith Norlyn', 'Custodio', 'LP (Las Pinas)'],
            ['Helen', 'Lasdoce', 'LP (Las Pinas)'],
            ['Kean Denzel', 'Perez', 'LP (Las Pinas)'],
            ['Kyle', 'Vela', 'LP (Las Pinas)'],

            ['Ilumi Pearl', 'Augusto', 'LTB (Buraeun)'],
            ['Daisyrie', 'Bandilla', 'LTB (Buraeun)'],
            ['Caira', 'Culas', 'LTB (Buraeun)'],
            ['Lucresia', 'Del Pilar', 'LTB (Buraeun)'],
            ['Aldwin', 'Gallamos', 'LTB (Buraeun)'],
            ['Angelita', 'Maroto', 'LTB (Buraeun)'],
            ['Lolito', 'Mostacesa', 'LTB (Buraeun)'],
            ['Marc Sydny', 'Mostacesa', 'LTB (Buraeun)'],
            ['Ma Nancy', 'Mostacesa', 'LTB (Buraeun)'],
            ['Emicelle', 'Refuerzo', 'LTB (Buraeun)'],
            ['Alma', 'Sudario', 'LTB (Buraeun)'],
            ['Manuel', 'Sudario', 'LTB (Buraeun)'],

            ['Ayiexha', 'Camiring', 'M1 (Soldiers/Bicutan)'],
            ['Segred Lictao', 'Cumeda', 'M1 (Soldiers/Bicutan)'],
            ['Milagros Lei', 'Limchu', 'M1 (Soldiers/Bicutan)'],
            ['Kenneth Mico Joe Caf', 'Panganiban', 'M1 (Soldiers/Bicutan)'],
            ['Jocel', 'Satsatin', 'M1 (Soldiers/Bicutan)'],
            ['Adelfa', 'Valdez', 'M1 (Soldiers/Bicutan)'],
            ['Blessie', 'Tabamo', 'M1 (Soldiers/Bicutan)'],

            ['Norma', 'Austria', 'M2 (Sta. Rosa)'],
            ['Abrielle', 'De Jesus', 'M2 (Sta. Rosa)'],
            ['Andrea', 'De Jesus', 'M2 (Sta. Rosa)'],
            ['Robert', 'Fronda', 'M2 (Sta. Rosa)'],

            ['Jayne Meagan', 'Escaro', 'M5 (Liberty Homes)'],
            ['Imee Rose', 'Escaro', 'M5 (Liberty Homes)'],
            ['Julliah Marie', 'Escaro', 'M5 (Liberty Homes)'],
            ['Evelegio', 'Macabebe', 'M5 (Liberty Homes)'],
            ['Lisly', 'Sordilla', 'M5 (Liberty Homes)'],
            ['Erin', 'Tanael', 'M5 (Liberty Homes)'],
            ['Cherry Mae', 'Tiamsing', 'M5 (Liberty Homes)'],
            ['Ralph Andrei', 'Zapanta', 'M5 (Liberty Homes)'],

            ['Pia Marchesa', 'Bue', 'M8 (Katarungan/Manggahan)'],
            ['Precious Ann', 'Opleda', 'M8 (Katarungan/Manggahan)'],

            ['Regina', 'Benipayo', 'MNL (Manila)'],
            ['Sahlee', 'Chua', 'MNL (Manila)'],
            ['Mary Rochel', 'Dizon', 'MNL (Manila)'],
            ['Merlie', 'Pamintuan', 'MNL (Manila)'],
            ['Marlowe', 'Pamintuan', 'MNL (Manila)'],
            ['Rommel', 'Pamintuan', 'MNL (Manila)'],
            ['Charito', 'Santos', 'MNL (Manila)'],
            ['Christian', 'Seneres', 'MNL (Manila)'],
            ['Angelica', 'Velasco', 'MNL (Manila)'],
            ['Eve', 'Villanueva', 'MNL (Manila)'],
            ['Evelyn', 'magno', 'MNL (Manila)'],

            ['Jeramie', 'Aragon', 'QC (Quezon City)'],
            ['Samantha Nicole', 'Cantonjos', 'QC (Quezon City)'],
            ['Ricardo', 'Vidal', 'QC (Quezon City)'],

            ['Rolando', 'Uy', 'SJB (San Juan Batangas)'],

            ['Josephine', 'Basañez', 'SP1 (Langgam/NHA/Batangas)'],
            ['Divina', 'Español', 'SP1 (Langgam/NHA/Batangas)'],
            ['Clarito', 'Esteleydes', 'SP1 (Langgam/NHA/Batangas)'],
            ['John David', 'Gatmaitan', 'SP1 (Langgam/NHA/Batangas)'],
            ['Evelyn', 'Zita', 'SP1 (Langgam/NHA/Batangas)'],

            ['Grace', 'Abanilla', 'SP2 (Brgy. UB)'],
            ['Queen', 'Abanilla', 'SP2 (Brgy. UB)'],
            ['Rachel', 'Pamor', 'SP2 (Brgy. UB)'],
            ['Clarice', 'Almodovar', 'SP2 (Brgy. UB)'],
            ['Frank Lloyd', 'Bautista', 'SP2 (Brgy. UB)'],
            ['John Aeron', 'Berroya', 'SP2 (Brgy. UB)'],
            ['Miccaela', 'Diaz', 'SP2 (Brgy. UB)'],
            ['Erwin', 'Sta. Rosa', 'SP2 (Brgy. UB)'],
            ['John Lexter', 'Tuico', 'SP2 (Brgy. UB)'],

            ['Raphael', 'Asis', 'SP4 (San Pedro-Bacoor-Paranaque)'],
            ['Mikayla', 'Botoon', 'SP4 (San Pedro-Bacoor-Paranaque)'],
            ['Ismael', 'Padernal', 'SP4 (San Pedro-Bacoor-Paranaque)'],

            ['Aileen', 'Juanicao', 'SP4CB1 (Landco)'],

            ['Thea Summer', 'Aquino', 'VAL Valenzuela'],
            ['Timothy George', 'Aquino', 'VAL Valenzuela'],
            ['Michael', 'Dacpano', 'VAL Valenzuela'],
            ['Alexis', 'Dolz', 'VAL Valenzuela'],
            ['Jun', 'Gaciles', 'VAL Valenzuela'],
            ['April', 'Gaciles', 'VAL Valenzuela'],
            ['Airalyn', 'Viado', 'VAL Valenzuela'],

            ['Luis', 'Bacunawa', null],
        ];

        foreach ($members as [$firstname, $lastname, $clusterGroup]) {
            $uuid = UUID::issue($event);

            DB::table('look_ups')->insert([
                'lamp_id' => $uuid,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'fullname' => trim($firstname . ' ' . $lastname),
                'cluster_group' => $clusterGroup,
                'can_book_days' => 4,
                'local_church' => 'Muntinlupa',
                'registration_type' => 'Member',
                'country' => 'Philippines',
                'avail_new_lamp_id' => 'no',
                'category' => 'Adult',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
