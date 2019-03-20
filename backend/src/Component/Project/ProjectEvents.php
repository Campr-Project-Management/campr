<?php

namespace Component\Project;

final class ProjectEvents
{
    public const PRE_CREATE = 'app.project.pre_create';

    public const POST_CREATE = 'app.project.post_create';

    public const PRE_UPDATE = 'app.project.pre_update';

    public const POST_UPDATE = 'app.project.post_update';

    public const ON_CLONE = 'app.project.clone';
}
