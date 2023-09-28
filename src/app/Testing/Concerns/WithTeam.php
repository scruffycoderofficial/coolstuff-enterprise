<?php

namespace App\Testing\Concerns;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

/**
 * Trait WithTeam
 *
 * @package App\Testing\Concerns
 */
trait WithTeam
{
    protected $actor;

    public function setupTeamActor()
    {
        $this->actor = User::factory()->create();
    }

    public function teamName()
    {
        return Str::replaceLast('.', '', $this->faker->text(15));
    }

    public function teamDescription()
    {
        return $this->faker->paragraph;
    }

    public function teamLogiFile()
    {
        return UploadedFile::fake()->create(Str::slug($this->faker->text(8)) . '.png', 1000);
    }

    public function teamManager()
    {
        $name = $this->faker->firstName . ' ' . $this->faker->lastName;

        return User::factory()->create(['name' => $name])->name;
    }
}
