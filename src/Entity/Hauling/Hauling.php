<?php

namespace App\Entity\Hauling;

use App\Entity\User;
use App\Repository\Hauling\HaulingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: HaulingRepository::class)]
class Hauling
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, Route>
     */
    #[ORM\OneToMany(targetEntity: Route::class, mappedBy: 'hauling', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $routes;

    #[ORM\ManyToOne(inversedBy: 'haulings')]
    private ?User $user = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $anonymous_user = null;

    public function __construct()
    {
        $this->routes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Route>
     */
    public function getRoutes(): Collection
    {
        return $this->routes;
    }

    public function addRoute(Route $route): static
    {
        if (!$this->routes->contains($route)) {
            $this->routes->add($route);
            $route->setHauling($this);
        }

        return $this;
    }

    public function removeRoute(Route $route): static
    {
        if ($this->routes->removeElement($route)) {
            // set the owning side to null (unless already changed)
            if ($route->getHauling() === $this) {
                $route->setHauling(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User|UserInterface|null $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getAnonymousUser(): ?string
    {
        return $this->anonymous_user;
    }

    public function setAnonymousUser(?string $anonymous_user): static
    {
        $this->anonymous_user = $anonymous_user;

        return $this;
    }
}
