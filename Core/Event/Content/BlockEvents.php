<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Content;

/**
 * Defines the names for the block events
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
final class BlockEvents
{
    // rkcms.event_listener

    const BEFORE_ADD_BLOCK = 'blocks.before_block_adding';
    const AFTER_ADD_BLOCK = 'blocks.after_block_added';

    const BEFORE_EDIT_BLOCK = 'blocks.before_block_editing';
    const AFTER_EDIT_BLOCK = 'blocks.after_block_edited';

    const BEFORE_DELETE_BLOCK = 'blocks.before_block_deleting';
    const AFTER_DELETE_BLOCK = 'blocks.after_block_deleted';
}
