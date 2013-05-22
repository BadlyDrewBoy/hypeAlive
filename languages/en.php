<?php

$english = array(

	'item:object:hjcomment' => 'Comments',
	
	'edit:plugin:hypealive:params[comments]' => 'Content comments',
	'edit:plugin:hypealive:params[comments]:hint' => 'Replace default Elgg comments',
	'edit:plugin:hypealive:params[river_comments]' => 'Activity stream comments',
	'edit:plugin:hypealive:params[river_comments]:hint' => 'Enable commenting of activity stream (river) items',
	'edit:plugin:hypealive:params[max_comment_depth]' => 'Depth of the comments tree',
	'edit:plugin:hypealive:params[max_comment_depth]:hint' => 'Maximum depth of the comments tree, i.e. 1 would only allow first level comments, 2 would allow comments on first level comments',
	'edit:plugin:hypealive:params[comment_form]' => 'Comment input style',
	'edit:plugin:hypealive:params[comment_form]:hint' => 'Simple form is a one-line form; Advanced form is a multiline form',
	'hj:alive:comment_form:simple' => 'Simple',
	'hj:alive:comment_form:advanced' => 'Advanced',
	'edit:plugin:hypealive:params[comment_form_position]' => 'Position of the comment input',
	'edit:plugin:hypealive:params[comment_form_position]:hint' => 'Comment input can be positioned before or after the comments list',
	'edit:plugin:hypealive:params[comments_order]' => 'Ordering of comments',
	'edit:plugin:hypealive:params[comments_order]:hint' => 'Comments can be displayed in chronological (oldest first) or reverse chronological (newest first) order',
	'hj:alive:comment_order:chronological' => 'Chronological (oldest to newest)',
	'hj:alive:comment_order:reverse_chronological' => 'Reverse (newest to oldest)',
	'edit:plugin:hypealive:params[comments_load_style]' => 'Viewing and loading of comments',
	'edit:plugin:hypealive:params[comments_load_style]:hint' => 'In cases, where the number of comments exceeds the initial display limit, there will be an option to show older/earlier comments',
	'hj:alive:comment_load_style:older' => 'Show latest comments with a link to load earlier comments',
	'hj:alive:comment_load_style:newer' => 'Show older comments with a link to load newer comments',
	'edit:plugin:hypealive:params[comments_limit]' => 'Initial number of comments to list',
	'edit:plugin:hypealive:params[comments_limit]:hint' => 'A number of comments to show before adding an option to load older/newer comments',
	'edit:plugin:hypealive:params[comments_load_limit]' => 'Number of comments to load per iteration',
	'edit:plugin:hypealive:params[comments_load_limit]:hint' => 'A number of comments that can be loaded at a time using the "show older/newer" link',

	'edit:plugin:hypealive:params[river]' => 'Live activity stream',
	'edit:plugin:hypealive:params[river]:hint' => 'Replace the default activity stream with a live activity stream equipped with live filters, pagers etc',
	'edit:plugin:hypealive:params[river_order]' => 'Activity stream ordering',
	'edit:plugin:hypealive:params[river_order]:hint' => 'Activity stream stories can be displayed in chronological (oldest first) or reverse chronological (newest first) order',
	'hj:alive:river_order:chronological' => 'Chronological (oldest to newest)',
	'hj:alive:river_order:reverse_chronological' => 'Reverse (newest to oldest)',
	'edit:plugin:hypealive:params[river_load_style]' => 'Viewing and loading of the activity stream',
	'edit:plugin:hypealive:params[river_load_style]:hint' => 'In cases, where the number of stories exceeds the initial display limit, there will be an option to show older/earlier stories',
	'hj:alive:river_load_style:older' => 'Show latest stories with a link to load earlier stories',
	'hj:alive:river_load_style:newer' => 'Show older stories with a link to load newer stories',
	'edit:plugin:hypealive:params[river_limit]' => 'Initial number of stories to show',
	'edit:plugin:hypealive:params[river_limit]:hint' => 'A number of stories to show before adding an option to load older/newer stories',
	'edit:plugin:hypealive:params[river_load_limit]' => 'Number of stories to load per iteration',
	'edit:plugin:hypealive:params[river_load_limit]:hint' => 'A number of stories that can be loaded at a time using the "show older/newer" link',

	'edit:plugin:hypealive:params[river_tabs][all]' => 'Activity Dashboard Tabs: Everyone',
	'edit:plugin:hypealive:params[river_tabs][mine]' => 'Activity Dashboard Tabs: Me',
	'edit:plugin:hypealive:params[river_tabs][friends]' => 'Activity Dashboard Tabs: My Friends',
	'edit:plugin:hypealive:params[river_tabs][groups]' => 'Activity Dashboard Tabs: My Groups',
	'edit:plugin:hypealive:params[river_tabs][subscriptions]' => 'Activity Dashboard Tabs: My Feed (Subscriptions)',
	'edit:plugin:hypealive:params[river_tabs]:hint' => 'Position of the tab (1,2,3..) Leave empty or set to 0 to disable this tab',

	
	'hj:alive:admin:upgrade' => 'Upgrade',
	'hj:alive:admin:upgrade_start' => 'Start Upgrade',
	'hj:alive:admin:upgrade_warning' => 'You are about to start a lengthy operation. Are you sure you want to continue?',
	'hj:alive:admin:upgrade_stats' => '%s items require upgrade',
	'hj:alive:admin:upgrade_complete' => 'Upgrade is complete. Reloading page...',

	'hj:alive:admin:import:comments' => 'Import Elgg comments',
	'hj:alive:admin:import:posts' => 'Import Elgg group discussion posts',
	'hj:alive:admin:import_start' => 'Start Import',
	'hj:alive:admin:import_warning' => 'Please note that THIS OPERATION IS IRREVERSIBLE. ',
	'hj:alive:admin:import_confirmation' => 'This operation is irreversible and may take a long time depending on the number of items being imported. Are you sure you want to continue?',
	'hj:alive:admin:import_stats' => '%s items can be imported',

	'hj:alive:admin:import_complete' => 'Import is complete. Reloading page ...',

	'hj:alive:river:tab:all' => 'Everyone',
	'hj:alive:river:tab:mine' => 'Me',
	'hj:alive:river:tab:friends' => 'My Friends',
	'hj:alive:river:tab:groups' => 'My Groups',
	'hj:alive:river:tab:subscriptions' => 'My Feed',
	'hj:alive:river:tab:bookmarks' => 'My Bookmarks',

	'river:groups' => 'Activity in my groups',
	'river:subscriptions' => 'My Interactions',

	'hj:alive:river:settings' => 'Feed Settings',
	'hj:alive:river:hidden_types_subtypes' => 'Hide all activity about:',
	'hj:alive:river:hidden_users' => 'Hide all activity from these users:',
	'hj:alive:river:hidden_groups' => 'Hide all activity from these groups:',

	'hj:alive:filter:tsp' => 'Filter activity by topic',
	'hj:alive:filter:users' => 'Filter activity by user',
	'hj:alive:filter:groups' => 'Filter activity by group',

	'hj:alive:river:load:next' => 'next %s stories &#9661;',
	'hj:alive:river:load:next:remaining' => 'next %s stories &#9661;',
	'hj:alive:river:load:next:all' => '%s stories &#9661;',

	'hj:alive:river:load:previous' => 'previous %s stories &#9651;',
	'hj:alive:river:load:previous:remaining' => 'previous %s stories &#9651;',
	'hj:alive:river:load:previous:all' => '%s stories &#8711;',

	'hj:alive:comments:load:next' => 'next %s comments &#9661;',
	'hj:alive:comments:load:next:remaining' => 'next %s comments &#9661;',
	'hj:alive:comments:load:next:all' => '%s comments &#9661;',

	'hj:alive:comments:load:previous' => 'previous %s comments &#9651;',
	'hj:alive:comments:load:previous:remaining' => 'previous %s comments &#9651;',
	'hj:alive:comments:load:previous:all' => '%s comments &#8711;',

	'hj:alive:reply' => 'Reply',
	'hj:alive:replies' => 'Replies',
	'hj:alive:replies:show' => '%s replies',
	'hj:alive:replies:hide' => 'Hide %s replies',

	'hj:alive:likes' => 'Likes',
	'hj:alive:like:create' => 'Like',
	'hj:alive:like:remove' => 'Unlike',
	'hj:alive:like:create:error' => 'Item can not be liked',
	'hj:alive:like:create:success' => 'You now like this item',
	'hj:alive:like:remove:error' => 'Item can not be unliked',
	'hj:alive:like:remove:success' => 'You no longer like this item',

	'hj:alive:bookmark:create' => 'Bookmark',
	'hj:alive:bookmark:remove' => 'Unbookmark',
	'hj:alive:bookmark:create:error' => 'Item can not be bookmarked',
	'hj:alive:bookmark:create:success' => 'Item successfully bookmarked',
	'hj:alive:bookmark:remove:error' => 'Bookmark can not be removed',
	'hj:alive:bookmark:remove:success' => 'Bookmark successfully removed',

	'hj:alive:subscription:create' => 'Follow',
	'hj:alive:subscription:remove' => 'Unfollow',
	'hj:alive:subscription:create:error' => 'You can\'t follow this item',
	'hj:alive:subscription:create:success' => 'You are now following this item',
	'hj:alive:subscription:remove:error' => 'Can not unfollow this item',
	'hj:alive:subscription:remove:success' => 'You are no longer following this item',

	'hj:alive:stream:notifications' => 'Followed Content Notifications',
	
	'hj:alive:share' => 'Share',
	'hj:alive:shares' => 'Shares',
	'hj:alive:share:success' => 'Item successfully shared',
	'hj:alive:share:error' => 'There was a problem sharing this item',
	
	'hj:alive:comment:email:subject' => 'New comment on your content',
	'hj:alive:comment:email:head' => '%s commented on your item %s:',
	'hj:alive:comment:email:footer' => 'You can view the original item and reply here: %s',
	'hj:alive:reply:email:subject' => 'New reply to your comment',
	'hj:alive:reply:email:head' => '%s reply to your comment on %s:',
	'hj:alive:reply:email:footer' => 'You can view the original item and reply here: %s',

	'hj:alive:stream:desc' => '%s\'s activity',
	
	'hj:alive:river:stream:bookmark' => '%s bookmarked %s',
	'hj:alive:river:stream:comment' => '%s commented on %s',
	'hj:alive:river:stream:like' => '%s liked %s',
	'hj:alive:river:stream:share' => '%s shared %s',
	'hj:alive:river:stream:subscription' => '%s started following %s',

	'hj:alive:river:substream:follow' => '%s started following this %s',
	'hj:alive:river:substream:bookmark' => '%s bookmarked this %s',
	'hj:alive:river:substream:comment' => '%s commented on this %s',
	'hj:alive:river:substream:like' => '%s liked this %s',
	'hj:alive:river:substream:share' => '%s shared this %s',
	
	/**
     * Comments
     */
    'hj:alive:comments:likebutton' => 'Like',
    'hj:alive:comments:unlikebutton' => 'Unlike',
	'hj:alive:comments:plusonebutton' => '+1 this',
    'hj:alive:comments:minusonebutton' => 'Remove +1',
    'hj:alive:comments:commentsbutton' => 'Comment',
	'hj:alive:comments:replybutton' => 'Reply',
    'hj:alive:comments:sharebutton' => 'Share',
    'hj:alive:comments:viewall' => 'View all %s comments',
    'hj:alive:comments:remainder' => 'View remaining %s comments',
    'hj:alive:comments:nocomments' => 'Be first to comment',
    'hj:comment:commenton' => 'Comment on %s',
    'hj:alive:comments:valuecantbeblank' => 'Comment can not be blank',

    'hj:alive:comments:lang:likes:you' => 'You ',
    'hj:alive:comments:lang:likes:and' => 'and ',
    'hj:alive:comments:lang:likes:others' => 'other people ',
    'hj:alive:comments:lang:likes:othersone' => 'other person ',
    'hj:alive:comments:lang:likes:people' => 'people ',
    'hj:alive:comments:lang:likes:peopleone' => 'person ',
    'hj:alive:comments:lang:likes:likethis' => 'like this',
    'hj:alive:comments:lang:likes:likesthis' => 'likes this',
	'hj:alive:comments:lang:likes:wholikesthis' => 'See who likes this',

	'hj:alive:comments:lang:plusone:you' => 'You ',
    'hj:alive:comments:lang:plusone:and' => 'and ',
    'hj:alive:comments:lang:plusone:others' => 'more',
    'hj:alive:comments:lang:plusone:othersone' => 'more',
    'hj:alive:comments:lang:plusone:people' => '',
    'hj:alive:comments:lang:plusone:peopleone' => '',
    'hj:alive:comments:lang:plusone:likethis' => '+1\'d this',
    'hj:alive:comments:lang:plusone:likesthis' => '',
	'hj:alive:comments:lang:plusone:wholikesthis' => 'See who +1\'d this',





    'hj:alive:comments:count' => 'comments',
    'hj:alive:comments:comments' => 'comments',
    'hj:alive:comments:delete' => 'Delete',
    'hj:alive:comments:newcomment' => 'Write a comment',

    'hj:alive:comments:addtopic' => 'Add new topic',
    'hj:alive:comments:forumtopictitle' => 'Enter your forum title...',
    'hj:alive:comments:forumtopicdescription' => 'Enter your forum message...',
    'eComents:forumtopicaddbutton' => 'Add',

    'hj:alive:comments:commentmissing' => 'Oh, your comment is missing',
    'hj:alive:comments:bodymissing' => 'Oh, you have not entered any text',
    'hj:alive:comments:topicmissing' => 'Oh, you need to enter a name for your forum topic',

	'search:comment' => 'Comment',
    'hj:alive:comments:commenton' => 'Comment on %s',
    'hj:alive:comments:commentcontent' => '%s: %s',
	'hj:alive:comment_on:river' => 'Comment on an activity: %s',

    'hj:comments:cantfind' => 'Oops, there was a problem adding your comment. The item must have been deleted',
    'hj:comments:savesuccess' => 'Your comment was added successfully',
    'hj:comments:refreshing' => 'Refreshing...',

    'hj:likes:savesuccess' => 'You now like this',
    'hj:likes:saveerror' => 'Sorry, we couldn\'t process your like',
    'hj:likes:likeremoved' => 'Your like was removed',

    /**
     * NOTIFICATIONS
     */
    'hj:comments:notify:activity_type:create' => '"created %s %s"',
    'hj:comments:notify:activity_type:update' => '"updated %s %s"',
    'hj:comments:notify:activity' => 'activity 
		
			%s'
	,

    'hj:comments:notify:post' => 'item titled %s',

    // Level 1
    'generic_comment:email:level1:subject' => 'You have a new comment',
    'generic_comment:email:level1:body' =>
            "You have a new comment from %s on your %s:
                
                %s

                You can reply here:
                %s.
            ",

    // Level 2
    'generic_comment:email:level2:subject' => 'New comment in a discussion thread',
    'generic_comment:email:level2:body' =>
            "There is a new comment from %s in a discussion on %s: <br />
                <br />
                <b>%s</b><br />
                <br />

                You can reply here: <br />
                %s.
            ",

    'group_topic_post:email:level1:subject' => 'New post on your group topic',
    'group_topic_post:email:level1:body' =>
            "You have a new post from %s on your %s: <br />
                <br />
                <b>%s</b><br />
                <br />

                You can reply here: <br />
                %s.
            ",

    'group_topic_post:email:level2:subject' => 'New group topic post',
    'group_topic_post:email:level2:body' =>
            "There is a new post from %s in a discussion on %s: <br />
                <br />
                <b>%s</b><br />
                <br />

                You can reply here: <br />
                %s.
            ",

    // Level 1
    'likes:email:level1:subject' => 'You have a new like',
    'likes:email:level1:body' =>
            "%s likes your %s <br />
            ",

    // Level 2
    'likes:email:level2:subject' => 'New like in a discussion thread',
    'likes:email:level2:body' =>
            "%s likes one of the responses in a discussion on %s<br />
                <br />
            ",

    /**
     * LiveSearch
     */
    'hj:alive:search:user' => 'Users',
    'hj:alive:search:group' => 'Groups',
    'hj:alive:search:blog' => 'Blogs',
    'hj:alive:search:bookmarks' => 'Bookmarks',
    'hj:alive:search:file' => 'Files',

	'search_types:group_topic_posts' => 'Discussion posts',
	'hj:alive:reply_to' => 'Reply to topic "%s" in group "%s"',
	
	'item:user:default' => 'User Profile',

	'hj:alive:button:clear' => 'Clear',
	

);

add_translation("en", $english);

?>