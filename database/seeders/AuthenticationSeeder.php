<?php

namespace Database\Seeders;

use App\Models\Catalogue;
use App\Models\Email;
use App\Models\Location;
use App\Models\Phone;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AuthenticationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createLocationCatalogues();
        $this->createIdentificationTypeCatalogues();
        $this->createSexCatalogues();
        $this->createGenderCatalogues();
        $this->createBloodTypeCatalogues();
        $this->createEthnicOriginCatalogues();
        $this->createCivilStatusCatalogues();
        $this->createSectorTypeCatalogues();
        $this->createTelephoneOperatorCatalogues();

        $this->createLocations();

        $this->createUsers();
        $this->createRoles();
        $this->createPermissions();
        $this->assignRolePermissions();
        $this->assignUserRoles();
    }

    private function createUsers()
    {
        $identificationTypes = Catalogue::where('type', 'IDENTIFICATION_TYPE')->get();
        $sexs = Catalogue::where('type', 'SEX_TYPE')->get();
        $genders = Catalogue::where('type', 'GENDER_TYPE')->get();
        $ethnicOrigin = Catalogue::where('type', 'ETHNIC_ORIGIN_TYPE')->get();
        $bloodType = Catalogue::where('type', 'BLOOD_TYPE')->get();
        $civilStatus = Catalogue::where('type', 'CIVIL_STATUS')->get();
        $operators = Catalogue::where('type', 'TELEPHONE_OPERATOR')->get();
        $locations = Location::where('type_id', 1)->get();
        $userFactory = User::factory()->create(
            [
                'username' => '1234567890',
                'identification_type_id' => $identificationTypes[rand(0, $identificationTypes->count() - 1)],
                'sex_id' => $sexs[rand(0, $sexs->count() - 1)],
                'gender_id' => $genders[rand(0, $genders->count() - 1)],
                'ethnic_origin_id' => $ethnicOrigin[rand(0, $ethnicOrigin->count() - 1)],
                'blood_type_id' => $bloodType[rand(0, $bloodType->count() - 1)],
                'civil_status_id' => $civilStatus[rand(0, $civilStatus->count() - 1)],
            ]
        );
        Phone::factory(2)->for($userFactory, 'phoneable')
            ->create(
                [
                    'operator_id' => $operators[rand(0, $operators->count() - 1)],
                    'location_id' => $locations[rand(0, $locations->count() - 1)]
                ]
            );
        Email::factory(2)->for($userFactory, 'emailable')->create();
        for ($i = 1; $i <= 151; $i++) {
            $userFactory = User::factory()
                ->create([
                    'identification_type_id' => $identificationTypes[rand(0, $identificationTypes->count() - 1)],
                    'sex_id' => $sexs[rand(0, $sexs->count() - 1)],
                    'gender_id' => $genders[rand(0, $genders->count() - 1)],
                    'ethnic_origin_id' => $ethnicOrigin[rand(0, $ethnicOrigin->count() - 1)],
                    'blood_type_id' => $bloodType[rand(0, $bloodType->count() - 1)],
                    'civil_status_id' => $civilStatus[rand(0, $civilStatus->count() - 1)],
                ]);
            Phone::factory(2)->for($userFactory, 'phoneable')
                ->create(['operator_id' => $operators[rand(0, $operators->count() - 1)]]);
            Email::factory(2)->for($userFactory, 'emailable')->create();
        }
    }

    private function createRoles()
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'guest']);
        Role::create(['name' => 'client']);
        Role::create(['name' => 'delivery']);
    }

    private function createPermissions()
    {
        Permission::create(['name' => 'view-users']);
        Permission::create(['name' => 'store-users']);
        Permission::create(['name' => 'update-users']);
        Permission::create(['name' => 'delete-users']);

        Permission::create(['name' => 'view-categories']);
        Permission::create(['name' => 'store-categories']);
        Permission::create(['name' => 'update-categories']);
        Permission::create(['name' => 'delete-categories']);

        Permission::create(['name' => 'view-enterprises']);
        Permission::create(['name' => 'store-enterprises']);
        Permission::create(['name' => 'update-enterprises']);
        Permission::create(['name' => 'delete-enterprises']);

        Permission::create(['name' => 'view-products']);
        Permission::create(['name' => 'store-products']);
        Permission::create(['name' => 'update-products']);
        Permission::create(['name' => 'delete-products']);

        Permission::create(['name' => 'view-clients']);
        Permission::create(['name' => 'store-clients']);
        Permission::create(['name' => 'update-clients']);
        Permission::create(['name' => 'delete-clients']);

        Permission::create(['name' => 'view-deliveries']);
        Permission::create(['name' => 'store-deliveries']);
        Permission::create(['name' => 'update-deliveries']);
        Permission::create(['name' => 'delete-deliveries']);

        Permission::create(['name' => 'view-favorites']);
        Permission::create(['name' => 'store-favorites']);
        Permission::create(['name' => 'update-favorites']);
        Permission::create(['name' => 'delete-favorites']);

        Permission::create(['name' => 'view-order-details']);
        Permission::create(['name' => 'store-order-details']);
        Permission::create(['name' => 'update-order-details']);
        Permission::create(['name' => 'delete-order-details']);

        Permission::create(['name' => 'view-enterprise-categories']);
        Permission::create(['name' => 'store-enterprise-categories']);
        Permission::create(['name' => 'update-enterprise-categories']);
        Permission::create(['name' => 'delete-enterprise-categories']);

        Permission::create(['name' => 'view-orders']);
        Permission::create(['name' => 'store-orders']);
        Permission::create(['name' => 'update-orders']);
        Permission::create(['name' => 'delete-orders']);

        Permission::create(['name' => 'download-files']);
        Permission::create(['name' => 'upload-files']);
        Permission::create(['name' => 'view-files']);
        Permission::create(['name' => 'update-files']);
        Permission::create(['name' => 'delete-files']);
    }

    private function assignRolePermissions()
    {
        $role = Role::firstWhere('name', 'admin');
        $role->syncPermissions(Permission::get());

        $role = Role::firstWhere('name', 'client');
        $role->givePermissionTo([
            'view-categories',
            'view-enterprises',
            'view-products',
            'view-favorites',
            'store-favorites',
            'update-favorites',
            'delete-favorites',
            'view-order-details',
            'store-order-details',
            'delete-order-details',
            'view-enterprise-categories',
            'view-orders',
            'store-orders',
            'upload-files'
        ]);
        $role = Role::firstWhere('name', 'delivery');
        $role->givePermissionTo([
            'view-enterprises',
            'view-products',
            'view-deliveries',
            'view-orders',
            'upload-files'
        ]);
    }

    private function assignUserRoles()
    {

        for ($i = 1; $i <= 151; $i++) {
            $user = User::find($i);
            if ($i == 1)
                $user->assignRole('admin');
            if ($i > 1 && $i <= 101)
                $user->assignRole('client');
            if ($i > 101 && $i <= 151)
                $user->assignRole('delivery');
            if ($i == 152)
                $user->assignRole('guest');
        }
    }

    private function createLocationCatalogues()
    {
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        Catalogue::factory(4)->sequence(
            [
                'code' => $catalogues['catalogue']['location']['country'],
                'name' => 'PAIS',
                'type' => $catalogues['catalogue']['location']['type'],
            ],
            [
                'code' => $catalogues['catalogue']['location']['province'],
                'name' => 'PROVINCIA',
                'type' => $catalogues['catalogue']['location']['type'],
            ],
            [
                'code' => $catalogues['catalogue']['location']['canton'],
                'name' => 'CANTON',
                'type' => $catalogues['catalogue']['location']['type'],
            ],
            [
                'code' => $catalogues['catalogue']['location']['parish'],
                'name' => 'PARROQUIA',
                'type' => $catalogues['catalogue']['location']['type'],
            ],
        )->create();
    }

    private function createLocations()
    {
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'1','AFGANISTÁN','AF','AFG','+93');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'2','ALBANIA','AL','ALB','+355');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'3','ALEMANIA','DE','DEU','+49');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'4','ANDORRA','AD','AND','+376');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'5','ANGOLA','AO','AGO','+244');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'6','ANGUILA','AI','AIA','+1264');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'7','ANTIGUA Y BARBUDA','AG','ATG','+1268');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'8','ARABIA SAUDITA','SA','SAU','+966');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'9','ARGELIA','DZ','DZA','+213');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'10','ARGENTINA','AR','ARG','+54');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'11','ARMENIA','AM','ARM','+374');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'12','ARUBA','AW','ABW','+297');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'13','AUSTRALIA','AU','AUS','+61');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'14','AUSTRIA','AT','AUT','+43');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'15','AZERBAIYÁN','AZ','AZE','+994');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'16','BAHAMAS','BS','BHS','+1242');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'17','BAHREIN','BH','BHR','+973');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'18','BANGLADESH','BD','BGD','+880');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'19','BARBADOS','BB','BRB','+1246');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'20','BÉLGICA','BB','BRB','+1246');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'21','BELICE','BZ','BLZ','+501');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'22','BENIN','BJ','BEN','+229');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'23','BERMUDAS','BJ','BEN','+229');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'24','BIELORRUSIA','BY','BLR','+375');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'25','BOLIVIA','BO','BOL','+591');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'26','BONAIRE, SAN EUSTAQUIO Y SABA','BO','BOL','+591');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'27','BOSNIA Y HERZEGOVINA','BA','BIH','+387');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'28','BOTSWANA','BW','BWA','+267');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'29','BRASIL','BR','BRA','+55');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'30','BRUNEI DARUSSALAM','BN','BRN','+673');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'31','BULGARIA','BG','BGR','+359');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'32','BURKINA FASO','BF','BFA','+226');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'33','BURUNDI','BI','BDI','+257');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'34','BUTÁN','BI','BDI','+257');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'35','CABO VERDE','CV','CPV','+238');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'36','CAMBOYA','KH','KHM','+855');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'37','CAMERÚN','CM','CMR','+237');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'38','CANADA','CA','CAN','+1');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'39','CHAD','TD','TCD','+235');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'40','CHILE','CL','CHL','+56');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'41','CHINA','CN','CHN','+86');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'42','CHIPRE','CY','CYP','+357');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'43','COLOMBIA','CO','COL','+57');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'44','COMORAS','KM','COM','+269');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'45','CONGO','KM','COM','+269');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'46','COREA DEL NORTE','KP','PRK','+850');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'47','COREA DEL SUR','KR','KOR','+82');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'48','COSTA DE MARﬁL','CI','CIV','+225');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'49','COSTA RICA','CR','CRI','+506');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'50','CROACIA','HR','HRV','+385');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'51','CUBA','CU','CUB','+53');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'52','CURAÇAO','CU','CUB','+53');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'53','DINAMARCA','DK','DNK','+45');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'54','DJIBOUTI','DK','DNK','+45');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'55','DOMINICA','DM','DMA','+1767');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'56','ECUADOR','EC','ECU','+593');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'57','EGIPTO','EG','EGY','+20');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'58','EL SALVADOR','SV','SLV','+503');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'59','EL VATICANO','SV','SLV','+503');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'60','EMIRATOS ÁRABES UNIDOS','AE','ARE','+971');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'61','ERITREA','ER','ERI','+291');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'62','ESLOVAQUIA','SK','SVK','+421');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'63','ESLOVENIA','SI','SVN','+386');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'64','ESPAÑA','ES','ESP','+34');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'65','ESTADO DE PALESTINA','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'66','ESTADOS UNIDOS DE AMÉRICA','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'67','ESTONIA','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'68','ETIOPÍA','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'69','FIYI','FJ','FJI','+679');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'70','FILIPINAS','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'71','FINLANDIA','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'72','FRANCIA','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'73','GABÓN','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'74','GAMBIA','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'75','GEORGIA','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'76','GHANA','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'77','GIBRALTAR','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'78','GRANADA','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'79','GRECIA','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'80','GROENLANDIA','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'81','GUADALUPE','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'82','GUAM','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'83','GUATEMALA','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'84','GUAYANA FRANCESA','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'85','GUERNSEY','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'86','GUINEA','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'87','GUINEA ECUATORIAL','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'88','GUINEA-BISSAU','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'89','GUYANA','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'90','HAITÍ','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'91','HONDURAS','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'92','HONG KONG','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'93','HUNGRÍA','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'94','INDIA','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'95','INDONESIA','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'96','IRAK','IQ','IRQ','+964');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'97','IRÁN','IM','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'98','IRLANDA','NF','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'99','ISLA DE MAN','IS','IMN','+44');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'100','ISLA NORFOLK','IS','NFK','+672');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'101','ISLANDIA','KY','ISL','+354');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'102','ISLAS ÅLAND','CK','ISL','+354');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'103','ISLAS CAIMÁN','FO','CYM','+1345');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'104','ISLAS COOK','FK','COK','+682');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'105','ISLAS FEROE','MP','FRO','+298');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'106','ISLAS MALVINAS (FALKLAND)','MH','FLK','+500');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'107','ISLAS MARIANAS DEL NORTE','SB','MNP','+1670');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'108','ISLAS MARSHALL','TC','MHL','+692');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'109','ISLAS SALOMÓN','UM','SLB','+677');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'110','ISLAS TURCAS Y CAICOS','VG','TCA','+1649');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'111','ISLAS VÍRGENES AMERICANAS','VI','UMI','+246');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'112','ISLAS VÍRGENES BRITÁNICAS','PS','VGB','+1284');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'113','ISLAS WALLIS Y FUTUNA','IT','VIR','+1340');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'114','ISRAEL','JM','PSE','+970');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'115','ITALIA','JP','ITA','+39');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'116','JAMAICA','JE','JAM','+1876');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'117','JAPÓN','JO','JPN','+81');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'118','JERSEY','KZ','JEY','+44');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'119','JORDANIA','KE','JOR','+962');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'120','KAZAJSTÁN','KG','KAZ','+7');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'121','KENYA','KI','KEN','+254');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'122','KIRGUISTÁN','KW','KGZ','+996');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'123','KIRIBATI','KW','KIR','+686');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'124','KUWAIT','KW','KWT','+965');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'125','LA EX REPÚBLICA YUGOSLAVA DE MACEDONIA','LV','KWT','+965');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'126','LESOTO','LV','KWT','+965');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'127','LETONIA','LR','LVA','+371');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'128','LÍBANO','LY','LVA','+371');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'129','LIBERIA','LI','LBR','+231');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'130','LIBIA','LT','LBY','+218');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'131','LIECHTENSTEIN','LU','LIE','+423');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'132','LITUANIA','LU','LTU','+370');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'133','LUXEMBURGO','LU','LUX','+352');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'134','MACAO','LU','LUX','+352');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'135','MADAGASCAR','MY','LUX','+352');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'136','MALASIA','MW','LUX','+352');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'137','MALAUI','ML','MYS','+60');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'138','MALDIVAS','MT','MWI','+265');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'139','MALÍ','MA','MLI','+223');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'140','MALTA','MQ','MLT','+356');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'141','MARRUECOS','MU','MAR','+212');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'142','MARTINICA','MR','MTQ','+596');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'143','MAURICIO','PS','MUS','+230');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'144','MAURITANIA','YT','MRT','+222');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'145','MAYOTTE','FM','PSE','+970');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'146','MÉXICO','MD','MYT','+262');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'147','MICRONESIA','MN','FSM','+691');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'148','MÓNACO','ME','MDA','+373');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'149','MONGOLIA','MS','MNG','+976');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'150','MONTENEGRO','MZ','MNE','+382');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'151','MONTSERRAT','MZ','MSR','+1664');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'152','MOZAMBIQUE','NA','MOZ','+258');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'153','MYANMAR','NR','MOZ','+258');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'154','NAMIBIA','NP','NAM','+264');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'155','NAURU','NI','NRU','+674');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'156','NEPAL','NE','NPL','+977');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'157','NICARAGUA','NG','NIC','+505');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'158','NÍGER','NU','NER','+227');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'159','NIGERIA','PS','NGA','+234');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'160','NIUE','NC','NIU','+683');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'161','NORUEGA','NZ','PSE','+970');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'162','NUEVA CALEDONIA','OM','NCL','+687');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'163','NUEVA ZELANDA','PS','NZL','+64');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'164','OMÁN','PK','OMN','+968');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'165','PAÍSES BAJOS','PS','PSE','+970');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'166','PAKISTÁN','PA','PAK','+92');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'167','PALAU','PG','PSE','+970');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'168','PANAMÁ','PY','PAN','+507');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'169','PAPÚA NUEVA GUINEA','PE','PNG','+675');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'170','PARAGUAY','PE','PRY','+595');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'171','PERÚ','PE','PER','+51');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'172','PITCAIRN','PL','PER','+51');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'173','POLINESIA FRANCÉS','PT','PER','+51');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'174','POLONIA','PR','POL','+48');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'175','PORTUGAL','QA','PRT','+351');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'176','PUERTO RICO','GB','PRI','+1');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'177','QATAR','GB','QAT','+974');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'178','REINO UNIDO DE GRAN BRETAÑA E IRLANDA DEL NORTE','CF','GBR','+44');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'179','REPÚBLICA ÁRABE SIRIA','CZ','GBR','+44');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'180','REPÚBLICA CENTROAFRICANA','CZ','CAF','+236');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'181','REPÚBLICA CHECA','SS','CZE','+420');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'182','REPÚBLICA DE MOLDAVIA','CZ','CZE','+420');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'183','REPÚBLICA DEMOCRÁTICA DEL CONGO','SS','SSD','+211');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'184','REPÚBLICA DEMOCRÁTICA POPULAR LAO','SS','CZE','+420');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'185','REPÚBLICA DOMINICANA','RE','SSD','+211');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'186','REPÚBLICA UNIDA DE TANZANIA','RO','SSD','+211');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'187','RÉUNION','RU','REU','+262');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'188','RUMANIA','RU','ROU','+40');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'189','RUSIA','EH','RUS','+7');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'190','RWANDA','EH','RUS','+7');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'191','SÁHARA OCCIDENTAL','EH','ESH','+212');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'192','SAINT-BARTHÉLEMY','WS','ESH','+212');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'193','SAINT-MARTIN','AS','ESH','+212');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'194','SAMOA','KN','WSM','+685');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'195','SAMOA AMERICANA','SM','ASM','+1684');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'196','SAN CRISTÓBAL Y NIEVES','PM','KNA','+1869');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'197','SAN MARINO','VC','SMR','+378');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'198','SAN PEDRO Y MIQUELÓN','SH','SPM','+508');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'199','SAN VICENTE Y LAS GRANADINAS','LC','VCT','+1784');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'200','SANTA ELENA','ST','SHN','+290');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'201','SANTA LUCÍA','SN','LCA','+1758');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'202','SANTO TOMÉ Y PRÍNCIPE','RS','STP','+239');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'203','SENEGAL','SC','SEN','+221');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'204','SERBIA','SL','SRB','+381');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'205','SEYCHELLES','SG','SYC','+248');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'206','SIERRA LEONA','SX','SLE','+232');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'207','SINGAPUR','SO','SGP','+65');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'208','SINT MAARTEN','LK','SMX','+1721');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'209','SOMALIA','ZA','SOM','+252');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'210','SRI LANKA','SD','LKA','+94');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'211','SUDÁFRICA','SD','ZAF','+27');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'212','SUDÁN','SE','SDN','+249');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'213','SUDÁN DEL SUR','CH','SDN','+249');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'214','SUECIA','SR','SWE','+46');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'215','SUIZA','SJ','CHE','+41');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'216','SURINAME','SZ','SUR','+597');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'217','SVALBARD Y JAN MAYEN','TH','SJM','+47');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'218','SWAZILANDIA','TJ','SWZ','+268');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'219','TAILANDIA','TL','THA','+66');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'220','TAYIKISTÁN','TG','TJK','+992');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'221','TIMOR-LESTE','TK','TLS','+670');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'222','TOGO','TO','TGO','+228');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'223','TOKELAU','TT','TKL','+690');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'224','TONGA','TN','TON','+676');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'225','TRINIDAD Y TOBAGO','TM','TTO','+1868');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'226','TÚNEZ','TR','TUN','+216');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'227','TURKMENISTÁN','TV','TKM','+993');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'228','TURQUÍA','UA','TUR','+90');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'229','TUVALU','UG','TUV','+688');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'230','UCRANIA','UY','UKR','+380');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'231','UGANDA','UZ','UGA','+256');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'232','URUGUAY','VU','URY','+598');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'233','UZBEKISTÁN','VE','UZB','+998');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'234','VANUATU','VE','VUT','+678');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'235','VENEZUELA','YE','VEN','+58');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'236','VIET NAM','ZM','VEN','+58');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'237','YEMEN','ZW','YEM','+967');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'238','ZAMBIA','ZM','ZMB','+260');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'239','ZIMBABWE','ZW','ZWE','+263');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'240','ANTÁRTIDA','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'241','ISLA BOUVET','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'242','TERRITORIO BRITÁNICO DE LA OCÉANO ÍNDICO','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'243','TAIWÁN','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'244','ISLA DE NAVIDAD','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'245','ISLAS COCOS','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'246','GEORGIA DEL SUR Y LAS ISLAS SANDWICH DEL SUR','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'247','TERRITORIOS AUSTRALES FRANCESES','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'999','NO REGISTRA','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,parent_id,code,name) values(2,56,'01','AZUAY');");
        DB::select("insert into authentication.locations(type_id,parent_id,code,name) values(2,56,'02','BOLIVAR');");
        DB::select("insert into authentication.locations(type_id,parent_id,code,name) values(2,56,'03','CAÑAR');");
        DB::select("insert into authentication.locations(type_id,parent_id,code,name) values(2,56,'04','CARCHI');");
        DB::select("insert into authentication.locations(type_id,parent_id,code,name) values(2,56,'05','COTOPAXI');");
        DB::select("insert into authentication.locations(type_id,parent_id,code,name) values(2,56,'06','CHIMBORAZO');");
        DB::select("insert into authentication.locations(type_id,parent_id,code,name) values(2,56,'07','EL ORO');");
        DB::select("insert into authentication.locations(type_id,parent_id,code,name) values(2,56,'08','ESMERALDAS');");
        DB::select("insert into authentication.locations(type_id,parent_id,code,name) values(2,56,'09','GUAYAS');");
        DB::select("insert into authentication.locations(type_id,parent_id,code,name) values(2,56,'10','IMBABURA');");
        DB::select("insert into authentication.locations(type_id,parent_id,code,name) values(2,56,'11','LOJA');");
        DB::select("insert into authentication.locations(type_id,parent_id,code,name) values(2,56,'12','LOS RIOS');");
        DB::select("insert into authentication.locations(type_id,parent_id,code,name) values(2,56,'13','MANABI');");
        DB::select("insert into authentication.locations(type_id,parent_id,code,name) values(2,56,'14','MORONA SANTIAGO');");
        DB::select("insert into authentication.locations(type_id,parent_id,code,name) values(2,56,'15','NAPO');");
        DB::select("insert into authentication.locations(type_id,parent_id,code,name) values(2,56,'16','PASTAZA');");
        DB::select("insert into authentication.locations(type_id,parent_id,code,name) values(2,56,'17','PICHINCHA');");
        DB::select("insert into authentication.locations(type_id,parent_id,code,name) values(2,56,'18','TUNGURAHUA');");
        DB::select("insert into authentication.locations(type_id,parent_id,code,name) values(2,56,'19','ZAMORA CHINCHIPE');");
        DB::select("insert into authentication.locations(type_id,parent_id,code,name) values(2,56,'20','GALAPAGOS');");
        DB::select("insert into authentication.locations(type_id,parent_id,code,name) values(2,56,'21','SUCUMBIOS');");
        DB::select("insert into authentication.locations(type_id,parent_id,code,name) values(2,56,'22','ORELLANA');");
        DB::select("insert into authentication.locations(type_id,parent_id,code,name) values(2,56,'23','SANTO DOMINGO DE LOS TSACHILAS');");
        DB::select("insert into authentication.locations(type_id,parent_id,code,name) values(2,56,'24','SANTA ELENA');");
        DB::select("insert into authentication.locations(type_id,parent_id,code,name) values(2,56,'90','ZONAS NO DELIMITADAS');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'1','AFGANISTÁN','AF','AFG','+93');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'2','ALBANIA','AL','ALB','+355');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'3','ALEMANIA','DE','DEU','+49');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'4','ANDORRA','AD','AND','+376');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'5','ANGOLA','AO','AGO','+244');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'6','ANGUILA','AI','AIA','+1264');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'7','ANTIGUA Y BARBUDA','AG','ATG','+1268');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'8','ARABIA SAUDITA','SA','SAU','+966');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'9','ARGELIA','DZ','DZA','+213');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'10','ARGENTINA','AR','ARG','+54');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'11','ARMENIA','AM','ARM','+374');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'12','ARUBA','AW','ABW','+297');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'13','AUSTRALIA','AU','AUS','+61');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'14','AUSTRIA','AT','AUT','+43');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'15','AZERBAIYÁN','AZ','AZE','+994');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'16','BAHAMAS','BS','BHS','+1242');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'17','BAHREIN','BH','BHR','+973');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'18','BANGLADESH','BD','BGD','+880');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'19','BARBADOS','BB','BRB','+1246');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'20','BÉLGICA','BB','BRB','+1246');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'21','BELICE','BZ','BLZ','+501');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'22','BENIN','BJ','BEN','+229');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'23','BERMUDAS','BJ','BEN','+229');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'24','BIELORRUSIA','BY','BLR','+375');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'25','BOLIVIA','BO','BOL','+591');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'26','BONAIRE, SAN EUSTAQUIO Y SABA','BO','BOL','+591');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'27','BOSNIA Y HERZEGOVINA','BA','BIH','+387');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'28','BOTSWANA','BW','BWA','+267');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'29','BRASIL','BR','BRA','+55');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'30','BRUNEI DARUSSALAM','BN','BRN','+673');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'31','BULGARIA','BG','BGR','+359');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'32','BURKINA FASO','BF','BFA','+226');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'33','BURUNDI','BI','BDI','+257');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'34','BUTÁN','BI','BDI','+257');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'35','CABO VERDE','CV','CPV','+238');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'36','CAMBOYA','KH','KHM','+855');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'37','CAMERÚN','CM','CMR','+237');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'38','CANADA','CA','CAN','+1');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'39','CHAD','TD','TCD','+235');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'40','CHILE','CL','CHL','+56');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'41','CHINA','CN','CHN','+86');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'42','CHIPRE','CY','CYP','+357');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'43','COLOMBIA','CO','COL','+57');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'44','COMORAS','KM','COM','+269');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'45','CONGO','KM','COM','+269');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'46','COREA DEL NORTE','KP','PRK','+850');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'47','COREA DEL SUR','KR','KOR','+82');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'48','COSTA DE MARﬁL','CI','CIV','+225');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'49','COSTA RICA','CR','CRI','+506');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'50','CROACIA','HR','HRV','+385');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'51','CUBA','CU','CUB','+53');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'52','CURAÇAO','CU','CUB','+53');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'53','DINAMARCA','DK','DNK','+45');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'54','DJIBOUTI','DK','DNK','+45');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'55','DOMINICA','DM','DMA','+1767');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'56','ECUADOR','EC','ECU','+593');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'57','EGIPTO','EG','EGY','+20');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'58','EL SALVADOR','SV','SLV','+503');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'59','EL VATICANO','SV','SLV','+503');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'60','EMIRATOS ÁRABES UNIDOS','AE','ARE','+971');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'61','ERITREA','ER','ERI','+291');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'62','ESLOVAQUIA','SK','SVK','+421');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'63','ESLOVENIA','SI','SVN','+386');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'64','ESPAÑA','ES','ESP','+34');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'65','ESTADO DE PALESTINA','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'66','ESTADOS UNIDOS DE AMÉRICA','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'67','ESTONIA','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'68','ETIOPÍA','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'69','FIYI','FJ','FJI','+679');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'70','FILIPINAS','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'71','FINLANDIA','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'72','FRANCIA','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'73','GABÓN','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'74','GAMBIA','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'75','GEORGIA','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'76','GHANA','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'77','GIBRALTAR','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'78','GRANADA','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'79','GRECIA','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'80','GROENLANDIA','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'81','GUADALUPE','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'82','GUAM','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'83','GUATEMALA','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'84','GUAYANA FRANCESA','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'85','GUERNSEY','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'86','GUINEA','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'87','GUINEA ECUATORIAL','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'88','GUINEA-BISSAU','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'89','GUYANA','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'90','HAITÍ','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'91','HONDURAS','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'92','HONG KONG','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'93','HUNGRÍA','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'94','INDIA','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'95','INDONESIA','SN','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'96','IRAK','IQ','IRQ','+964');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'97','IRÁN','IM','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'98','IRLANDA','NF','SN','SN');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'99','ISLA DE MAN','IS','IMN','+44');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'100','ISLA NORFOLK','IS','NFK','+672');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'101','ISLANDIA','KY','ISL','+354');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'102','ISLAS ÅLAND','CK','ISL','+354');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'103','ISLAS CAIMÁN','FO','CYM','+1345');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'104','ISLAS COOK','FK','COK','+682');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'105','ISLAS FEROE','MP','FRO','+298');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'106','ISLAS MALVINAS (FALKLAND)','MH','FLK','+500');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'107','ISLAS MARIANAS DEL NORTE','SB','MNP','+1670');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'108','ISLAS MARSHALL','TC','MHL','+692');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'109','ISLAS SALOMÓN','UM','SLB','+677');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'110','ISLAS TURCAS Y CAICOS','VG','TCA','+1649');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'111','ISLAS VÍRGENES AMERICANAS','VI','UMI','+246');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'112','ISLAS VÍRGENES BRITÁNICAS','PS','VGB','+1284');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'113','ISLAS WALLIS Y FUTUNA','IT','VIR','+1340');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'114','ISRAEL','JM','PSE','+970');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'115','ITALIA','JP','ITA','+39');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'116','JAMAICA','JE','JAM','+1876');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'117','JAPÓN','JO','JPN','+81');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'118','JERSEY','KZ','JEY','+44');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'119','JORDANIA','KE','JOR','+962');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'120','KAZAJSTÁN','KG','KAZ','+7');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'121','KENYA','KI','KEN','+254');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'122','KIRGUISTÁN','KW','KGZ','+996');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'123','KIRIBATI','KW','KIR','+686');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'124','KUWAIT','KW','KWT','+965');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'125','LA EX REPÚBLICA YUGOSLAVA DE MACEDONIA','LV','KWT','+965');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'126','LESOTO','LV','KWT','+965');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'127','LETONIA','LR','LVA','+371');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'128','LÍBANO','LY','LVA','+371');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'129','LIBERIA','LI','LBR','+231');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'130','LIBIA','LT','LBY','+218');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'131','LIECHTENSTEIN','LU','LIE','+423');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'132','LITUANIA','LU','LTU','+370');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'133','LUXEMBURGO','LU','LUX','+352');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'134','MACAO','LU','LUX','+352');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'135','MADAGASCAR','MY','LUX','+352');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'136','MALASIA','MW','LUX','+352');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'137','MALAUI','ML','MYS','+60');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'138','MALDIVAS','MT','MWI','+265');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'139','MALÍ','MA','MLI','+223');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'140','MALTA','MQ','MLT','+356');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'141','MARRUECOS','MU','MAR','+212');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'142','MARTINICA','MR','MTQ','+596');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'143','MAURICIO','PS','MUS','+230');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'144','MAURITANIA','YT','MRT','+222');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'145','MAYOTTE','FM','PSE','+970');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'146','MÉXICO','MD','MYT','+262');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'147','MICRONESIA','MN','FSM','+691');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'148','MÓNACO','ME','MDA','+373');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'149','MONGOLIA','MS','MNG','+976');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'150','MONTENEGRO','MZ','MNE','+382');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'151','MONTSERRAT','MZ','MSR','+1664');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'152','MOZAMBIQUE','NA','MOZ','+258');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'153','MYANMAR','NR','MOZ','+258');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'154','NAMIBIA','NP','NAM','+264');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'155','NAURU','NI','NRU','+674');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'156','NEPAL','NE','NPL','+977');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'157','NICARAGUA','NG','NIC','+505');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'158','NÍGER','NU','NER','+227');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'159','NIGERIA','PS','NGA','+234');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'160','NIUE','NC','NIU','+683');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'161','NORUEGA','NZ','PSE','+970');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'162','NUEVA CALEDONIA','OM','NCL','+687');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'163','NUEVA ZELANDA','PS','NZL','+64');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'164','OMÁN','PK','OMN','+968');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'165','PAÍSES BAJOS','PS','PSE','+970');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'166','PAKISTÁN','PA','PAK','+92');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'167','PALAU','PG','PSE','+970');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'168','PANAMÁ','PY','PAN','+507');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'169','PAPÚA NUEVA GUINEA','PE','PNG','+675');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'170','PARAGUAY','PE','PRY','+595');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'171','PERÚ','PE','PER','+51');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'172','PITCAIRN','PL','PER','+51');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'173','POLINESIA FRANCÉS','PT','PER','+51');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'174','POLONIA','PR','POL','+48');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'175','PORTUGAL','QA','PRT','+351');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'176','PUERTO RICO','GB','PRI','+1');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'177','QATAR','GB','QAT','+974');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'178','REINO UNIDO DE GRAN BRETAÑA E IRLANDA DEL NORTE','CF','GBR','+44');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'179','REPÚBLICA ÁRABE SIRIA','CZ','GBR','+44');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'180','REPÚBLICA CENTROAFRICANA','CZ','CAF','+236');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'181','REPÚBLICA CHECA','SS','CZE','+420');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'182','REPÚBLICA DE MOLDAVIA','CZ','CZE','+420');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'183','REPÚBLICA DEMOCRÁTICA DEL CONGO','SS','SSD','+211');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'184','REPÚBLICA DEMOCRÁTICA POPULAR LAO','SS','CZE','+420');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'185','REPÚBLICA DOMINICANA','RE','SSD','+211');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'186','REPÚBLICA UNIDA DE TANZANIA','RO','SSD','+211');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'187','RÉUNION','RU','REU','+262');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'188','RUMANIA','RU','ROU','+40');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'189','RUSIA','EH','RUS','+7');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'190','RWANDA','EH','RUS','+7');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'191','SÁHARA OCCIDENTAL','EH','ESH','+212');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'192','SAINT-BARTHÉLEMY','WS','ESH','+212');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'193','SAINT-MARTIN','AS','ESH','+212');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'194','SAMOA','KN','WSM','+685');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'195','SAMOA AMERICANA','SM','ASM','+1684');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'196','SAN CRISTÓBAL Y NIEVES','PM','KNA','+1869');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'197','SAN MARINO','VC','SMR','+378');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'198','SAN PEDRO Y MIQUELÓN','SH','SPM','+508');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'199','SAN VICENTE Y LAS GRANADINAS','LC','VCT','+1784');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'200','SANTA ELENA','ST','SHN','+290');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'201','SANTA LUCÍA','SN','LCA','+1758');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'202','SANTO TOMÉ Y PRÍNCIPE','RS','STP','+239');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'203','SENEGAL','SC','SEN','+221');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'204','SERBIA','SL','SRB','+381');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'205','SEYCHELLES','SG','SYC','+248');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'206','SIERRA LEONA','SX','SLE','+232');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'207','SINGAPUR','SO','SGP','+65');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'208','SINT MAARTEN','LK','SMX','+1721');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'209','SOMALIA','ZA','SOM','+252');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'210','SRI LANKA','SD','LKA','+94');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'211','SUDÁFRICA','SD','ZAF','+27');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'212','SUDÁN','SE','SDN','+249');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'213','SUDÁN DEL SUR','CH','SDN','+249');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'214','SUECIA','SR','SWE','+46');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'215','SUIZA','SJ','CHE','+41');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'216','SURINAME','SZ','SUR','+597');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'217','SVALBARD Y JAN MAYEN','TH','SJM','+47');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'218','SWAZILANDIA','TJ','SWZ','+268');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'219','TAILANDIA','TL','THA','+66');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'220','TAYIKISTÁN','TG','TJK','+992');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'221','TIMOR-LESTE','TK','TLS','+670');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'222','TOGO','TO','TGO','+228');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'223','TOKELAU','TT','TKL','+690');");
        DB::select("insert into authentication.locations(type_id,code,name,alpha2_code,alpha3_code,calling_code) values(1,'224','TONGA','TN','TON','+676');");
    }

    private function createIdentificationTypeCatalogues()
    {
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        Catalogue::factory(3)->sequence(
            [
                'code' => $catalogues['catalogue']['identification_type']['cc'],
                'name' => 'CEDULA',
                'type' => $catalogues['catalogue']['identification_type']['type'],
            ],
            [
                'code' => $catalogues['catalogue']['identification_type']['passport'],
                'name' => 'PASAPORTE', 'type' => $catalogues['catalogue']['identification_type']['type']
            ],
            [
                'code' => $catalogues['catalogue']['identification_type']['ruc'],
                'name' => 'RUC', 'type' => $catalogues['catalogue']['identification_type']['type']
            ],
        )->create();
    }

    private function createSexCatalogues()
    {
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        Catalogue::factory(2)->sequence(
            [
                'code' => $catalogues['catalogue']['sex']['male'],
                'name' => 'HOMBRE',
                'type' => $catalogues['catalogue']['sex']['type']
            ],
            [
                'code' => $catalogues['catalogue']['sex']['female'],
                'name' => 'MUJER',
                'type' => $catalogues['catalogue']['sex']['type'],
            ]
        )->create();
    }

    private function createGenderCatalogues()
    {
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        Catalogue::factory(4)->sequence(
            [
                'code' => $catalogues['catalogue']['gender']['male'],
                'name' => 'MASCULINO',
                'type' => $catalogues['catalogue']['gender']['type']
            ],
            [
                'code' => $catalogues['catalogue']['gender']['female'],
                'name' => 'FEMENINO',
                'type' => $catalogues['catalogue']['gender']['type'],
            ],
            [
                'code' => $catalogues['catalogue']['gender']['other'],
                'name' => 'OTRO',
                'type' => $catalogues['catalogue']['gender']['type'],
            ],
            [
                'code' => $catalogues['catalogue']['gender']['not_say'],
                'name' => 'PREFIERO NO DECIRLO',
                'type' => $catalogues['catalogue']['gender']['type'],
            ],
        )->create();
    }

    private function createSectorTypeCatalogues()
    {
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        Catalogue::factory(3)->sequence(
            [
                'name' => 'NORTE',
                'type' => $catalogues['catalogue']['sector']['type'],
            ],
            [
                'name' => 'CENTRO',
                'type' => $catalogues['catalogue']['sector']['type'],
            ],
            [
                'name' => 'SUR',
                'type' => $catalogues['catalogue']['sector']['type'],
            ],
        )->create();
    }

    private function createEthnicOriginCatalogues()
    {
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        Catalogue::factory(9)->sequence(
            [
                'code' => $catalogues['catalogue']['ethnic_origin']['indigena'],
                'name' => 'INDIGENA',
                'type' => $catalogues['catalogue']['ethnic_origin']['type'],
            ],
            [
                'code' => $catalogues['catalogue']['ethnic_origin']['afroecuatoriano'],
                'name' => 'AFROECUATORIANO',
                'type' => $catalogues['catalogue']['ethnic_origin']['type'],
            ],
            [
                'code' => $catalogues['catalogue']['ethnic_origin']['negro'],
                'name' => 'NEGRO',
                'type' => $catalogues['catalogue']['ethnic_origin']['type'],
            ],
            [
                'code' => $catalogues['catalogue']['ethnic_origin']['mulato'],
                'name' => 'MULATO',
                'type' => $catalogues['catalogue']['ethnic_origin']['type'],
            ],
            [
                'code' => $catalogues['catalogue']['ethnic_origin']['montuvio'],
                'name' => 'MONTUVIO',
                'type' => $catalogues['catalogue']['ethnic_origin']['type'],
            ],
            [
                'code' => $catalogues['catalogue']['ethnic_origin']['mestizo'],
                'name' => 'MESTIZO',
                'type' => $catalogues['catalogue']['ethnic_origin']['type'],
            ],
            [
                'code' => $catalogues['catalogue']['ethnic_origin']['blanco'],
                'name' => 'BLANCO',
                'type' => $catalogues['catalogue']['ethnic_origin']['type'],
            ],
            [
                'code' => $catalogues['catalogue']['ethnic_origin']['other'],
                'name' => 'OTRO',
                'type' => $catalogues['catalogue']['ethnic_origin']['type'],
            ],
            [
                'code' => $catalogues['catalogue']['ethnic_origin']['unregistered'],
                'name' => 'NO REGISTRA',
                'type' => $catalogues['catalogue']['ethnic_origin']['type'],
            ]
        )->create();
    }

    private function createBloodTypeCatalogues()
    {
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        Catalogue::factory(8)->sequence(
            [
                'code' => $catalogues['catalogue']['blood_type']['a-'],
                'name' => 'A-',
                'type' => $catalogues['catalogue']['blood_type']['type'],
            ],
            [
                'code' => $catalogues['catalogue']['blood_type']['a+'],
                'name' => 'A+',
                'type' => $catalogues['catalogue']['blood_type']['type'],
            ],
            [
                'code' => $catalogues['catalogue']['blood_type']['b-'],
                'name' => 'B-',
                'type' => $catalogues['catalogue']['blood_type']['type'],
            ],
            [
                'code' => $catalogues['catalogue']['blood_type']['b+'],
                'name' => 'B+',
                'type' => $catalogues['catalogue']['blood_type']['type'],
            ],
            [
                'code' => $catalogues['catalogue']['blood_type']['ab-'],
                'name' => 'AB-',
                'type' => $catalogues['catalogue']['blood_type']['type'],
            ],
            [
                'code' => $catalogues['catalogue']['blood_type']['ab+'],
                'name' => 'AB+',
                'type' => $catalogues['catalogue']['blood_type']['type'],
            ],
            [
                'code' => $catalogues['catalogue']['blood_type']['o-'],
                'name' => 'O-',
                'type' => $catalogues['catalogue']['blood_type']['type'],
            ],
            [
                'code' => $catalogues['catalogue']['blood_type']['o+'],
                'name' => 'O+',
                'type' => $catalogues['catalogue']['blood_type']['type'],
            ],
        )->create();
    }

    private function createCivilStatusCatalogues()
    {
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        Catalogue::factory(8)->sequence(
            [
                'code' => $catalogues['catalogue']['civil_status']['married'],
                'name' => 'CASADO/A',
                'type' => $catalogues['catalogue']['civil_status']['type'],
            ],
            [
                'code' => $catalogues['catalogue']['civil_status']['single'],
                'name' => 'SOLTERO/A',
                'type' => $catalogues['catalogue']['civil_status']['type'],
            ],
            [
                'code' => $catalogues['catalogue']['civil_status']['divorced'],
                'name' => 'DIVORCIADO/A',
                'type' => $catalogues['catalogue']['civil_status']['type'],
            ],
            [
                'code' => $catalogues['catalogue']['civil_status']['widower'],
                'name' => 'VIUDO/A',
                'type' => $catalogues['catalogue']['civil_status']['type'],
            ],
            [
                'code' => $catalogues['catalogue']['civil_status']['union'],
                'name' => 'UNION DE HECHO',
                'type' => $catalogues['catalogue']['civil_status']['type'],
            ],
        )->create();
    }

    private function createTelephoneOperatorCatalogues()
    {
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        Catalogue::factory(3)->sequence(
            [
                'name' => 'CLARO',
                'type' => $catalogues['catalogue']['telephone_operator']['type'],
            ],
            [
                'name' => 'CNT',
                'type' => $catalogues['catalogue']['telephone_operator']['type'],
            ],
            [
                'name' => 'MOVISTAR',
                'type' => $catalogues['catalogue']['telephone_operator']['type'],
            ],
        )->create();
    }
}
