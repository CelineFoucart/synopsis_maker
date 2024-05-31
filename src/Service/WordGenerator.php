<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Episode;
use App\Entity\Synopsis;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Style\Language;
use PhpOffice\PhpWord\Shared\Converter;
use Doctrine\Common\Collections\Collection;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\SimpleType\TextAlignment;
use PhpOffice\PhpWord\Style\Font;

final class WordGenerator
{
    private PhpWord $phpWord;

    private Synopsis $synopsis;

    public function __construct(private $tmpDir)
    {
        Settings::setZipClass(Settings::PCLZIP);
        $this->phpWord = new PhpWord();
    }
    
    public function setSynopsis(Synopsis $synopsis): self
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    /**
     * Create the file and return an associated array with the path and the filename.
     * 
     * @return array
     */
    public function generate(): array
    {
        $this->setStyle()->setProperties()->setIntroductionSection();

        if ($this->synopsis->getSettings()['appendChapters']) {
            $this->setChapterSection();
        } else if($this->synopsis->getSettings()['appendEpisodes']) {
            $this->setEpisodeSection();
        }

        if ($this->synopsis->getSettings()['appendCharacters']) {
            $this->setCharactersSection();
        }

        if ($this->synopsis->getSettings()['appendPlaces']) {
            $this->setPlacesSection();
        }

        if ($this->synopsis->getSettings()['appendWorldBuildingHome'] || $this->synopsis->getSettings()['appendArticles']) {
            $this->setWorldBuildingSection();
        }

        if ($this->synopsis->getSettings()['appendNotes']) {
            $this->setNotesSection();
        }

        return $this->saveFile();
    }

    /**
     * Append to the file the synopsis general informations.
     */
    private function setIntroductionSection(): self
    {
        $section = $this->phpWord->addSection();
        $section->addTitle($this->synopsis->getTitle(), 0);
        
        if (!$this->synopsis->getCategories()->isEmpty() && $this->synopsis->getSettings()['appendCategories']) {
            $categories = $this->reduceCollectionToString($this->synopsis->getCategories());
            $section->addText($categories, ['bold' => true, 'italic' => true], ['spaceAfter' => Converter::cmToTwip(0.6)]);
        }

        if ($this->synopsis->getPitch() && $this->synopsis->getSettings()['appendPitch']) {
            $textWithBreakLines = str_replace("\n", '</w:t><w:br/><w:t xml:space="preserve">', $this->synopsis->getPitch());
            $section->addText(
                'Pitch : '.$textWithBreakLines,
                ['bold' => true, 'italic' => true],
                ['spaceAfter' => Converter::cmToTwip(0.6)]
            );
        }

        if ($this->synopsis->getDescription() && $this->synopsis->getSettings()['appendDescription']) {
            HTML::addHtml($section, $this->synopsis->getDescription());
        }

        return $this;
    }

    private function setChapterSection(): self
    {
        $section = $this->phpWord->addSection();
        $section->addTitle('Déroulé', 1);

        foreach ($this->synopsis->getChapters() as $chapter) {
            if ($this->synopsis->getSettings()['appendChapterTitles']) {
                $section->addTitle($chapter->getTitle(), 2);
            }

            if ($this->synopsis->getSettings()['appendChapterTitles'] && $chapter->getDescription()) {
                $textWithBreakLines = str_replace("\n", '</w:t><w:br/><w:t xml:space="preserve">', $chapter->getDescription());
                $section->addText($textWithBreakLines, ['italic' => true], ['spaceAfter' => Converter::cmToTwip(0.6)]);
            }

            if (!$this->synopsis->getSettings()['appendEpisodes']) {
                continue;
            }

            foreach ($chapter->getEpisodes() as $episode) {
                $this->appendEpisode($episode, $section);
            }


        }

        if ($this->synopsis->getSettings()['appendEpisodes']) {
            foreach ($this->synopsis->getEpisodes() as $episode) {
                if ($episode->getChapterId() === null) {
                    $this->appendEpisode($episode, $section, 2);
                }
            }
        }

        return $this;
    }

    private function setEpisodeSection(): self
    {
        $section = $this->phpWord->addSection();
        $section->addTitle('Déroulé', 1);

        foreach ($this->synopsis->getEpisodes() as $episode) {
            $this->appendEpisode($episode, $section, 2);
        }

        return $this;
    }

    private function appendEpisode(Episode $episode, Section $section, int $titleNumber = 3): Section
    {
        if ($this->synopsis->getSettings()['appendEpisodeTitles']) {
            $section->addTitle($episode->getTitle(), $titleNumber);
        }

        if ($this->synopsis->getSettings()['appendEpisodeTitles'] && !$episode->getCharacters()->isEmpty()) {
            $characters = $this->reduceCollectionToString($episode->getCharacters());
            $section->addText(
                'Personnages associés : ' . $characters, 
                ['bold' => true, 'italic' => true], ['spaceAfter' => Converter::cmToTwip(0.6)])
            ;
        }

        if ($this->synopsis->getSettings()['appendEpisodePlaces'] && !$episode->getPlaces()->isEmpty()) {
            $places = $this->reduceCollectionToString($episode->getPlaces());
            $section->addText(
                'Lieux associés : ' . $places, 
                ['bold' => true, 'italic' => true], ['spaceAfter' => Converter::cmToTwip(0.6)])
            ;
        }

        HTML::addHtml($section, $episode->getContent());

        return $section;
    }

    private function setCharactersSection(): self
    {
        $section = $this->phpWord->addSection();
        $section->addTitle('Personnages', 1);

        foreach ($this->synopsis->getCharacters() as $character) {
            $section->addTitle($character->getName(), 2);
            $section->addText(str_replace("\n", '</w:t><w:br/><w:t xml:space="preserve">', $character->getDescription()));

            if ($character->getBirthday()) {
                $section->addListItem('Naissance : ' . $character->getBirthday());
            }

            if ($character->getBirthdayPlace()) {
                $section->addListItem('Lieu de naissance : ' . $character->getBirthdayPlace());
            }

            if ($character->getDeathDate()) { 
                $section->addListItem('Mort : ' . $character->getDeathDate());
            }

            if ($character->getDeathPlace()) {
                $section->addListItem('Lieu de mort : ' . $character->getDeathPlace());
            }

            if ($character->getSpecies()) {
                $section->addListItem('Espèce : ' . $character->getSpecies());
            }

            if ($character->getGender()) {
                $section->addListItem('Genre : ' . $character->getGender());
            }
            
            if ($character->getNationality()) {
                $section->addListItem('Nationalité : ' . $character->getNationality());
            }

            if ($character->getJob()) {
                $section->addListItem('Emploi : ' . $character->getJob());
            }
            if ($character->getFaction()) {
                $section->addListItem('Faction : ' . $character->getFaction());
            }

            if ($character->getMembership()) {
                $section->addListItem('Affiliation : ' . $character->getMembership());
            }

            if ($character->getParents()) {
                $section->addListItem('Parents : ' . $character->getParents());
            }

            if ($character->getSiblings()) {
                $section->addListItem('Fratrie : ' . $character->getSiblings());
            }

            if ($character->getPartner()) {
                $section->addListItem('Conjoint(e) : ' . $character->getPartner());
            }

            if ($character->getChildren()) {
                $section->addListItem('Enfants : ' . $character->getChildren());
            }

            if ($character->getComplementary()) {
                $section->addListItem('Informations complémentaires : ' . $character->getComplementary());
            }

            if ($character->getLink()) {
                $section->addListItem('Voir aussi : ' . $character->getLink());
            }
            
            if ($character->getAppearance()) {
                $section->addTitle('Apparence', 3);
                HTML::addHtml($section, $character->getAppearance());
            }

            if ($character->getBiography()) {
                $section->addTitle('Biographie', 3);
                HTML::addHtml($section, $character->getBiography());
            }

            if ($character->getPersonality() !== null && count($character->getPersonality()) > 0 && $character->getPersonality()[0]['key'] !== null) {
                $section->addTitle('Personnalité', 3);
                foreach ($character->getPersonality() as $line) {
                    $section->addListItem($line['key'] . ' : ' . $line['content']);
                }
            }
        }

        return $this;
    }

    private function setPlacesSection(): self
    {
        $section = $this->phpWord->addSection();
        $section->addTitle('Lieux', 1);

        foreach ($this->synopsis->getPlaces() as $place) {
            $section->addTitle($place->getTitle(), 2);
            if ($place->getRole()) {
                $section->addText(str_replace("\n", '</w:t><w:br/><w:t xml:space="preserve">', $place->getRole()));
            }

            if ($place->getComplementary()) {
                $section->addListItem('Informations complémentaires : ' . $place->getComplementary());
            }

            if ($place->getLink()) {
                $section->addListItem('Voir aussi : ' . $place->getLink());
            }

            HTML::addHtml($section, $place->getDescription());

        }

        return $this;
    }

    private function setWorldBuildingSection(): self
    {
        $section = $this->phpWord->addSection();
        $section->addTitle('Univers', 1);

        if ($this->synopsis->getWorldbuildingHome() &&  $this->synopsis->getSettings()['appendWorldBuildingHome']) {
            HTML::addHtml($section, $this->synopsis->getWorldbuildingHome());
        }

        if (!$this->synopsis->getSettings()['appendArticles']) {
            return $this;
        }

        foreach ($this->synopsis->getArticles() as $article) {
            $section->addTitle($article->getTitle(), 2);

            if ($article->getCategory()) {
                $section->addText($article->getCategory()->getTitle(), ['italic' => true], ['spaceAfter' => Converter::cmToTwip(0.6)]);
                HTML::addHtml($section, $article->getContent());
            }
        }

        return $this;
    }

    private function setNotesSection(): self
    {
        $section = $this->phpWord->addSection();
        $section->addTitle('Notes et réflexions', 1);
        HTML::addHtml($section, $this->synopsis->getNotes());

        return $this;
    }

    /**
     * Save the file.
     * 
     * @return array an associated array with the path and the filename.
     */
    private function saveFile(): array
    {
        $objWriter = IOFactory::createWriter($this->phpWord, 'Word2007');
        $objWriter->save($this->tmpDir.DIRECTORY_SEPARATOR.$this->synopsis->getSlug().'.docx');

        return [
            'path' => $this->tmpDir.DIRECTORY_SEPARATOR.$this->synopsis->getSlug().'.docx',
            'filename' => $this->synopsis->getSlug().'.docx',
        ];
    }

    /**
     * Set the properties of the file with the array returned by the method getParamProperties.
     * The the key title and created are mandatory.
     */
    private function setProperties(): self
    {
        $properties = $this->phpWord->getDocInfo();
        $properties
            ->setTitle($this->synopsis->getTitle())
            ->setDescription($this->synopsis->getPitch() ? $this->synopsis->getPitch() : '')
            ->setCategory($this->reduceCollectionToString($this->synopsis->getCategories()))
            ->setCreated($this->synopsis->getCreatedAt()->getTimestamp())
            ->setModified($this->synopsis->getUpdatedAt() ? $this->synopsis->getUpdatedAt()->getTimestamp() : null)
            ->setSubject('Synopsis')
        ;

        return $this;
    }

    /**
     * Define the style.
     */
    private function setStyle(): static
    {
        $this->phpWord->addTitleStyle(0, ['size' => 40, 'bold' => true]);
        $this->phpWord->addTitleStyle(
            1, 
            ['size' => 30,  'bold' => true, 'smallCaps' => true, 'bgColor' => '21252908'], 
            ['spaceBefore' => Converter::cmToTwip(0.5), 'alignment' => Jc::CENTER]
        );
        $this->phpWord->addTitleStyle(2, ['size' => 20,  'bold' => true, 'underline' => Font::UNDERLINE_SINGLE]);
        $this->phpWord->addTitleStyle(3, ['size' => 15,  'bold' => true, 'underline' => Font::UNDERLINE_SINGLE]);
        $this->phpWord->setDefaultFontName('Times New Roman');
        $this->phpWord->setDefaultFontSize(12);
        $this->phpWord->getSettings()->setThemeFontLang(new Language(Language::FR_FR));

        return $this;
    }

    /**
     * Reduce a collection of entity with the _toString implemented.
     */
    private function reduceCollectionToString(Collection $elements): string
    {
        $titles = array_map(fn ($value): string => (string) $value, $elements->toArray());

        return join(', ', $titles);
    }
}
