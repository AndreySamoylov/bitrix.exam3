<?php

namespace Academy\InvestmentProject\Integration\Intranet\Employee;

use Bitrix\Intranet\Component\UserProfile;
use Bitrix\Intranet\UserTable;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Context\Culture;
use Bitrix\Main\SystemException;
use CComponentEngine;
use CUser;

class Service
{
    private static Collection $cache;

    public function __construct(private readonly Culture $culture)
    {
    }

    /**
     * @throws ServiceException
     */
    public function getById(int $id): ?Employee
    {
        return $this->getByIds($id)->get($id);
    }

    /**
     * @param int ...$ids
     * @return Collection
     *
     * @throws ServiceException
     */
    public function getByIds(int ...$ids): Collection
    {
        $cache = Service::$cache ??= new Collection();

        $result = new Collection();
        foreach ($ids as $index => $id) {
            $item = $cache->get($id);

            if ($item !== null) {
                // Item present in cache - add to result.
                $result->insert($item);
                unset($ids[$index]);
            }
        }

        if (empty($ids)) {
            // All data was present in cache.
            return $result;
        }

        try {
            $users = UserTable::query()
                ->setSelect([
                    'ID',
                    'PERSONAL_PHOTO',
                    'NAME',
                    'LAST_NAME',
                    'SECOND_NAME',
                    'LOGIN',
                    'WORK_POSITION'
                ])
                ->where('USER_TYPE_IS_EMPLOYEE', true)
                ->where('ACTIVE', '=', 'Y')
                ->whereIn('ID', $ids)
                ->exec();
        } catch (SystemException $e) {
            throw new ServiceException('Failed to find employees', previous: $e);
        }

        while ($user = $users->fetch()) {
            $id = (int)$user['ID'];

            $item = new Employee(
                $id,
                $user['NAME'] ?? null,
                $user['LAST_NAME'] ?? null,
                $user['SECOND_NAME'] ?? null,
                $user['LOGIN'] ?? null,
                $this->getPersonalPhotoPath($user['PERSONAL_PHOTO'] ?? 0),
                $this->getProfileUrl($id),
                $this->formatName($user),
                $user['WORK_POSITION'] ?? ''
            );
            $result->insert($item);
            $cache->insert($item);
        }

        return $result;
    }

    public function getPersonalPhotoPath(int $photoId, ?int $size = null): ?string
    {
        return UserProfile::getUserPhoto($photoId, $size) ?: null;
    }

    public function getProfileUrl(int $userId): string
    {
        return CComponentEngine::makePathFromTemplate($this->getProfileUrlTemplate(), ['USER_ID' => $userId]);
    }

    public function getProfileUrlTemplate(): string
    {
        return Option::get('intranet', 'path_user') ?: '/company/personal/user/#USER_ID#/';
    }

    public function formatName(array $userInfo): string
    {
        return CUser::FormatName($this->culture->getNameFormat(), $userInfo);
    }
}