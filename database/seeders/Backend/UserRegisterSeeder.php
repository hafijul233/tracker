<?php

namespace Database\Seeders\Backend;

use App\Models\Backend\Common\Address;
use App\Models\Backend\Setting\Role;
use App\Models\Backend\Setting\User;
use App\Repositories\Eloquent\Backend\Common\AddressBookRepository;
use App\Repositories\Eloquent\Backend\Setting\UserRepository;
use App\Services\Backend\Common\FileUploadService;
use App\Supports\Constant;
use App\Supports\Utility;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Throwable;

class UserRegisterSeeder extends Seeder
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var FileUploadService
     */
    private $fileUploadService;
    /**
     * @var AddressBookRepository
     */
    private $addressBookRepository;


    /**
     * UserSeeder constructor.
     * @param UserRepository $userRepository
     * @param FileUploadService $fileUploadService
     * @param AddressBookRepository $addressBookRepository
     */
    public function __construct(UserRepository $userRepository,
                                FileUploadService $fileUploadService,
                                AddressBookRepository $addressBookRepository)
    {
        $this->userRepository = $userRepository;
        $this->fileUploadService = $fileUploadService;
        $this->addressBookRepository = $addressBookRepository;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception|Throwable
     */
    public function run()
    {
        Model::unguard();
        //disable Observer
        $eventDispatcher = User::getEventDispatcher();
        User::unsetEventDispatcher();

        //Default User "Ami"

        try {
            $newUser = [
                'name' => 'Mohammad Hafijul Islam',
                'username' => 'hafijul233',
                'email' => 'hafijul233@gmail.com',
                'password' => Utility::hashPassword(Constant::PASSWORD),
                'mobile' => '01710534092',
                'remarks' => 'Database Seeder',
                'enabled' => Constant::ENABLED_OPTION
            ];

            $newUser = $this->userRepository->create($newUser);
            if ($newUser instanceof User) {
                if (!$this->attachProfilePicture($newUser)) {
                    //throw new \RuntimeException("User Photo Create Failed");
                }

                if (!$this->attachUserRoles($newUser)) {
                    throw new \RuntimeException("User Role Assignment Failed");
                }

                if (!$this->attachHomeAddress($newUser)) {
                    throw new \RuntimeException("User Address Assignment Failed");
                }
            } else {
                throw new \RuntimeException("Failed to Create  User Model");
            }
        } catch (Exception $exception) {
            $this->userRepository->handleException($exception);
        }

        //Enable observer
        User::setEventDispatcher($eventDispatcher);
    }

    /**
     * Attach Profile Image to User Model
     *
     * @param User $user
     * @return bool
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     * @throws Exception
     */
    protected function attachProfilePicture(User $user): bool
    {
        //add profile image
        $profileImagePath = $this->fileUploadService->createAvatarImageFromText($user->name);
        if (is_string($profileImagePath)) {
            return $user->addMedia($profileImagePath)->toMediaCollection('avatars')->save();
        }
        return false;
    }

    /**
     * Attach Role to user Model
     *
     * @param User $user
     * @return bool
     */
    protected function attachUserRoles(User $user): bool
    {

        $adminRole = Role::findByName(Constant::SUPER_ADMIN_ROLE);
        return (bool)$user->assignRole($adminRole);
//        $this->userRepository->setModel($user);
//        return $this->userRepository->manageRoles([$adminRole->id]);
    }

    /**
     * Attach user contact address
     *
     * @param User $user
     * @return bool
     * @throws Exception
     */
    protected function attachHomeAddress(User $user): bool
    {
        $address = [
            'addressable_type' => get_class($user),
            'addressable_id' => $user->id,
            'type' => 'home',
            'phone' => $user->mobile,
            'name' => 'Mohammad Mustak Ahmed',
            'street_1' => 'Hamida Vila, 334/1 No Baherchor, Vakutta',
            'street_2' => 'Shalampur, Savar',
            'url' => 'https://goo.gl/maps/F3jmV27XfAC3ABSt8',
            'longitude' => 23.7511307,
            'latitude' => 90.3015192,
            'post_code' => 1310,
            'fallback' => Constant::DISABLED_OPTION,
            'enabled' => Constant::ENABLED_OPTION,
            'remark' => 'testing real data',
            'city_id' => config('contact.default.city'),
            'state_id' => config('contact.default.state'),//Dhaka
            'country_id' => config('contact.default.country') //Bangladesh
        ];

        return ($this->addressBookRepository->create($address) instanceof Address);
    }
}
