<?php

namespace AppBundle\Command;

use AppBundle\Entity\Media;
use AppBundle\Repository\MediaRepository;
use Gaufrette\Exception\FileNotFound;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MediaCleanCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:media:clean')
            ->setDescription('Deletes all unused media files')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->cleaningMedias($input, $output);
        $this->cleaningChunks($input, $output);

        return 0;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    private function cleaningMedias(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $medias = $this->getMedias();
        $count = count($medias);

        $io->title('Deleting orphan medias...');
        if (!$count) {
            $io->success('No orphan medias found.');

            return 0;
        }

        $progress = new ProgressBar($output, $count);
        $deleted = 0;
        $errors = [];
        foreach ($medias as $media) {
            try {
                $this->removeMedia($media);
                ++$deleted;
            } catch (\Exception $e) {
                $errors[] = [
                    'file' => $media->getFileName(),
                    'message' => $e->getMessage(),
                ];
            }

            $progress->advance(1);
        }

        if (!empty($errors)) {
            foreach ($errors as $error) {
                $io->error(sprintf('Error removing file "%s": %s', $error['file'], $error['message']));
            }
        }

        $io->newLine(2);
        $io->success(sprintf('%d of %d orphan media files successfully deleted', $deleted, $count));

        return $deleted;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    private function cleaningChunks(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Deleting uploaded chunks...');
        $manager = $this
            ->getContainer()
            ->get('oneup_uploader.chunk_manager')
        ;
        $manager->clear();
        $io->success('Uploaded chunks successfully deleted.');
    }

    /**
     * @param Media $media
     */
    private function removeMedia(Media $media)
    {
        try {
            $this
                ->getMediaRepository()
                ->remove($media)
            ;
        } catch (FileNotFound $e) {
        }
    }

    /**
     * @return MediaRepository
     */
    private function getMediaRepository(): MediaRepository
    {
        return $this
            ->getContainer()
            ->get('app.repository.media')
        ;
    }

    /**
     * @return Media[]
     */
    private function getMedias(): array
    {
        return $this
            ->getMediaRepository()
            ->findAllExpired()
        ;
    }
}
