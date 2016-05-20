<?php
class UserTableSeeder extends Seeder {

    public function run()
    {
        // to use non Eloquent-functions we need to unguard
        Eloquent::unguard();

        // All existing users are deleted !!!
        DB::table('users')->delete();

        // add user using Eloquent
        $user = new User;
        $user->firstname = 'Admin';
        $user->lastname = 'Test';
        $user->username = 'admin';
        $user->hashed_password = Hash::make('admin');
        $user->password = 'admin';
        $user->email = 'vpatel@infonius.com';
        $user->organization = 'infonius';
        $user->status = 'enabled';
        $user->save();

        // alternativ to eloquent we can also use direct database-methods
        /*
        User::create(array(
            'username'  => 'admin',
            'password'  => Hash::make('password'),
            'email'     => 'admin@localhost'
        ));
        */
}
}