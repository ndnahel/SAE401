<?php

namespace App\Entity;

use App\Repository\FavoriteCityRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavoriteCityRepository::class)]
class FavoriteCity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
	
	#[ORM\ManyToOne(targetEntity: User::class, fetch: 'EAGER', inversedBy: 'favoriteCities')]
	#[ORM\JoinColumn(nullable: false)]
	private ?User $user = null;

	#[ORM\Column]
	private ?int $city_id = null;
	
	/**
	 * @return int|null
	 */
    public function getId(): ?int
    {
        return $this->id;
    }
	
	/**
	 * @return int|null
	 */
	public function getCityId(): ?int
	{
		return $this->city_id;
	}
	
	/**
	 * @param int $city_id
	 * @return $this
	 */
	public function setCityId(int $city_id): static
	{
		$this->city_id = $city_id;
		return $this;
	}
	
	/**
	 * @return User|null
	 */
	public function getUser(): ?User
	{
		return $this->user;
	}
	
	/**
	 * @param User $user
	 * @return $this
	 */
	public function setUser(User $user): static
	{
		$this->user = $user;
		return $this;
	}
}
