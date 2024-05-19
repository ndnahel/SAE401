<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $username = null;
	
	#[ORM\Column(length: 200)]
	private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];
	
	#[ORM\Column(type: 'json')]
	private ?array $preferences = [];

    /**
     * @var string|null The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;
	
	#[ORM\Column(type: 'string', length: 255, nullable: true)]
	private ?string $resetToken = null;
	
	#[ORM\OneToMany(targetEntity: FavoriteCity::class, mappedBy: 'user', fetch: 'EAGER')]
	private Collection $favoriteCities;
	
	public function __construct()
	{
		$this->favoriteCities = new ArrayCollection();
	}
	
	/**
	 * @return int|null
	 */
	public function getId(): ?int
    {
        return $this->id;
    }
	
	/**
	 * @return string|null
	 */
    public function getUsername(): ?string
    {
        return $this->username;
    }
	
	/**
	 * @param string $username
	 * @return $this
	 */
    public function setUsername(string $username): static
    {
        $this->username = $username;
        return $this;
    }
	
	/**
	 * @return string|null
	 */
	public function getEmail(): ?string
	{
		return $this->email;
	}
	
	/**
	 * @param string $email
	 * @return $this
	 */
	public function setEmail(string $email): static
	{
		$this->email = $email;
		return $this;
	}

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }
	
	/**
	 * @param string $password
	 * @return $this
	 */
    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
	
	/**
	 * @return array
	 */
	public function getPreferences(): array {
		return $this->preferences;
	}
	
	/**
	 * @return string
	 */
	public function getLang(): string {
		return $this->preferences['lang'];
	}
	
	/**
	 * @param string $lang
	 * @return User
	 */
	public function setLang(string $lang): static {
		$this->preferences['lang'] = $lang;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getUnit(): string {
		return $this->preferences['unit'];
	}
	
	/**
	 * @param string $unit
	 * @return User
	 */
	public function setUnit(string $unit): static {
		$this->preferences['unit'] = $unit;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getCountry(): string {
		return $this->preferences['country'];
	}
	
	/**
	 * @param string $country
	 * @return User
	 */
	public function setCountry(string $country): static {
		$this->preferences['country'] = $country;
		return $this;
	}
	
	/**
	 * @param array $preferences
	 * @return User|null
	 */
	public function setPreferences(array $preferences): ?User {
		$this->preferences = $preferences;
		return $this;
	}
	
	/**
	 * @param string $name
	 * @param string $value
	 * @return $this
	 */
	public function addPreference(string $name, string $value): static {
		$this->preferences[$name] = $value;
		return $this;
	}
	
	/**
	 * @return string|null
	 */
	public function getResetToken(): ?string
	{
		return $this->resetToken;
	}
	
	/**
	 * @param string|null $resetToken
	 * @return $this
	 */
	public function setResetToken(?string $resetToken): static
	{
		$this->resetToken = $resetToken;
		return $this;
	}
	
	/**
	 * @return Collection
	 */
	public function getFavoriteCities(): Collection
	{
		return $this->favoriteCities;
	}
	
	/**
	 * @param FavoriteCity $favoriteCity
	 * @return $this
	 */
	public function addFavoriteCity(FavoriteCity $favoriteCity): static
	{
		if (!$this->favoriteCities->contains($favoriteCity)) {
			$this->favoriteCities[] = $favoriteCity;
			$favoriteCity->setUser($this);
		}
		return $this;
	}
}
