<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property-read mixed $color
 * @property-read mixed $icon
 * @property-read \App\Models\Project|null $project
 * @property-read \App\Models\Task|null $task
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Activity byType($type)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity forProject($projectId)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Activity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Activity query()
 * @method static \Illuminate\Database\Eloquent\Builder|Activity recent($limit = 10)
 */
	class Activity extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $project_id
 * @property string $board_name
 * @property string|null $description
 * @property int|null $position
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Card> $cards
 * @property-read int|null $cards_count
 * @property-read \App\Models\Project $project
 * @method static \Illuminate\Database\Eloquent\Builder|Board newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Board newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Board query()
 * @method static \Illuminate\Database\Eloquent\Builder|Board whereBoardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Board whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Board whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Board whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Board wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Board whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Board whereUpdatedAt($value)
 */
	class Board extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $board_id
 * @property string $card_title
 * @property string|null $description
 * @property int $position
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $due_date
 * @property string|null $status
 * @property string|null $priority
 * @property string|null $estimated_hours
 * @property string|null $actual_hours
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $assignedMembers
 * @property-read int|null $assigned_members_count
 * @property-read \App\Models\User|null $assignedUser
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $assignedUsers
 * @property-read int|null $assigned_users_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CardAssignment> $assignments
 * @property-read int|null $assignments_count
 * @property-read \App\Models\Board $board
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\User $creator
 * @property-read mixed $completed_subtasks_count
 * @property-read mixed $days_until_due
 * @property-read mixed $hours_remaining
 * @property-read mixed $is_due_soon
 * @property-read mixed $is_overdue
 * @property-read mixed $priority_label
 * @property-read mixed $status_label
 * @property-read mixed $subtasks_progress
 * @property-read mixed $title
 * @property-read mixed $total_hours_worked
 * @property-read mixed $total_subtasks_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $members
 * @property-read int|null $members_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Subtask> $subtasks
 * @property-read int|null $subtasks_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TimeLog> $timeLogs
 * @property-read int|null $time_logs_count
 * @method static \Illuminate\Database\Eloquent\Builder|Card assignedTo($userId)
 * @method static \Illuminate\Database\Eloquent\Builder|Card dueSoon()
 * @method static \Illuminate\Database\Eloquent\Builder|Card newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Card newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Card ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|Card overdue()
 * @method static \Illuminate\Database\Eloquent\Builder|Card priority($priority)
 * @method static \Illuminate\Database\Eloquent\Builder|Card query()
 * @method static \Illuminate\Database\Eloquent\Builder|Card status($status)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereActualHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereBoardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereCardTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereEstimatedHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereUpdatedAt($value)
 */
	class Card extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CardAssignment> $assignments
 * @property-read int|null $assignments_count
 * @property-read \App\Models\Board|null $board
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Subtask> $subtasks
 * @property-read int|null $subtasks_count
 * @method static \Illuminate\Database\Eloquent\Builder|Card2 newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Card2 newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Card2 query()
 */
	class Card2 extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $card_id
 * @property int $user_id
 * @property string|null $assignment_status
 * @property string|null $started_at
 * @property string|null $completed_at
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read \App\Models\Card $card
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|CardAssignment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CardAssignment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CardAssignment query()
 * @method static \Illuminate\Database\Eloquent\Builder|CardAssignment whereAssignmentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardAssignment whereCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardAssignment whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardAssignment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardAssignment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardAssignment whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardAssignment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardAssignment whereUserId($value)
 */
	class CardAssignment extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $card_id
 * @property int|null $subtask_id
 * @property int $user_id
 * @property string $comment_text
 * @property string $comment_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Card|null $card
 * @property-read \App\Models\Subtask|null $subtask
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Comment cardComments()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment subtaskComments()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCommentText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCommentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereSubtaskId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUserId($value)
 */
	class Comment extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $sender_id
 * @property int $receiver_id
 * @property string $message
 * @property bool $is_read
 * @property \Illuminate\Support\Carbon|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $formatted_time
 * @property-read \App\Models\User $receiver
 * @property-read \App\Models\User $sender
 * @method static \Illuminate\Database\Eloquent\Builder|Message betweenUsers($userId1, $userId2)
 * @method static \Illuminate\Database\Eloquent\Builder|Message newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message query()
 * @method static \Illuminate\Database\Eloquent\Builder|Message unread()
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereIsRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereReceiverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereUpdatedAt($value)
 */
	class Message extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string|null $avatar
 * @property string|null $bio
 * @property string|null $phone
 * @property string|null $address
 * @property \Illuminate\Support\Carbon|null $date_of_birth
 * @property string|null $website
 * @property array|null $social_links
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $avatar_url
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Profile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Profile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Profile query()
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereBio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereSocialLinks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereWebsite($value)
 */
	class Profile extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $status
 * @property string $project_name
 * @property string|null $description
 * @property string|null $thumbnail
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $deadline
 * @property string|null $github_link
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Board> $boards
 * @property-read int|null $boards_count
 * @property-read \App\Models\User $creator
 * @property-read mixed $active_members
 * @property-read mixed $completed_tasks
 * @property-read mixed $progress
 * @property-read mixed $thumbnail_image
 * @property-read mixed $thumbnail_url
 * @property-read mixed $total_tasks
 * @property-read \App\Models\User|null $leader
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProjectMember> $members
 * @property-read int|null $members_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $members2
 * @property-read int|null $members2_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $superAdmin
 * @property-read int|null $super_admin_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Card> $tasks
 * @property-read int|null $tasks_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $teamLeads
 * @property-read int|null $team_leads_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $teamMembers
 * @property-read int|null $team_members_count
 * @method static \Illuminate\Database\Eloquent\Builder|Project completed()
 * @method static \Illuminate\Database\Eloquent\Builder|Project newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project ongoing()
 * @method static \Illuminate\Database\Eloquent\Builder|Project query()
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereDeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereGithubLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereProjectName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereThumbnail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project withThumbnail()
 */
	class Project extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $project_id
 * @property int $user_id
 * @property string $role
 * @property string $joined_at
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read mixed $user_full_name
 * @property-read mixed $user_name
 * @property-read mixed $user_role
 * @property-read \App\Models\Project $project
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectMember active()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectMember byRole($role)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectMember byUserRole($roles)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectMember developers()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectMember newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectMember newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectMember query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectMember teamLeads()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectMember whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectMember whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectMember whereJoinedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectMember whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectMember whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectMember whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectMember whereUserId($value)
 */
	class ProjectMember extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $card_id
 * @property string $subtask_title
 * @property string|null $description
 * @property string|null $status
 * @property string|null $estimated_hours
 * @property string|null $actual_hours
 * @property int $position
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Card $card
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read mixed $status_label
 * @property-read mixed $total_hours_worked
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TimeLog> $timeLogs
 * @property-read int|null $time_logs_count
 * @method static \Illuminate\Database\Eloquent\Builder|Subtask completed()
 * @method static \Illuminate\Database\Eloquent\Builder|Subtask newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subtask newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subtask ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|Subtask query()
 * @method static \Illuminate\Database\Eloquent\Builder|Subtask status($status)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtask whereActualHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtask whereCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtask whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtask whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtask whereEstimatedHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtask whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtask wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtask whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtask whereSubtaskTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtask whereUpdatedAt($value)
 */
	class Subtask extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \App\Models\Card|null $card
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TimeLog> $timeLogs
 * @property-read int|null $time_logs_count
 * @method static \Illuminate\Database\Eloquent\Builder|Subtask1 newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subtask1 newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subtask1 query()
 */
	class Subtask1 extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User|null $assignedUser
 * @property-read \App\Models\Board|null $board
 * @property-read mixed $priority_color
 * @property-read mixed $status_color
 * @method static \Illuminate\Database\Eloquent\Builder|Task assignedTo($userId)
 * @method static \Illuminate\Database\Eloquent\Builder|Task byPriority($priority)
 * @method static \Illuminate\Database\Eloquent\Builder|Task byStatus($status)
 * @method static \Illuminate\Database\Eloquent\Builder|Task newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Task newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Task query()
 */
	class Task extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $card_id
 * @property int|null $subtask_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon $start_time
 * @property \Illuminate\Support\Carbon|null $end_time
 * @property int|null $duration_minutes
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Card $card
 * @property-read mixed $duration_hours
 * @property-read mixed $elapsed_time
 * @property-read mixed $formatted_duration
 * @property-read mixed $is_running
 * @property-read \App\Models\Subtask|null $subtask
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|TimeLog active()
 * @method static \Illuminate\Database\Eloquent\Builder|TimeLog completed()
 * @method static \Illuminate\Database\Eloquent\Builder|TimeLog forUser($userId)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TimeLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TimeLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|TimeLog thisWeek()
 * @method static \Illuminate\Database\Eloquent\Builder|TimeLog today()
 * @method static \Illuminate\Database\Eloquent\Builder|TimeLog whereCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeLog whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeLog whereDurationMinutes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeLog whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeLog whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeLog whereSubtaskId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeLog whereUserId($value)
 */
	class TimeLog extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \App\Models\Card|null $card
 * @property-read \App\Models\Subtask|null $subtask
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|TimeLog1 newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TimeLog1 newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TimeLog1 query()
 */
	class TimeLog1 extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string|null $phone
 * @property string|null $bio
 * @property string|null $avatar
 * @property mixed $password
 * @property string $full_name
 * @property string $role
 * @property string $current_task_status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\TimeLog|null $activeTimeLog
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Card> $assignedCards
 * @property-read int|null $assigned_cards_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CardAssignment> $assignments
 * @property-read int|null $assignments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CardAssignment> $cardAssignments
 * @property-read int|null $card_assignments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Project> $createdProjects
 * @property-read int|null $created_projects_count
 * @property-read \App\Models\CardAssignment|null $currentTask
 * @property-read mixed $avatar_url
 * @property-read mixed $display_name
 * @property-read mixed $is_online
 * @property-read mixed $role_badge_color
 * @property-read mixed $status_color
 * @property-read int|null $unread_messages_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Project> $ledProjects
 * @property-read int|null $led_projects_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $members
 * @property-read int|null $members_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Profile|null $profile
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProjectMember> $projectMembers
 * @property-read int|null $project_members_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Project> $projects
 * @property-read int|null $projects_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message> $receivedMessages
 * @property-read int|null $received_messages_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message> $sentMessages
 * @property-read int|null $sent_messages_count
 * @property-read \App\Models\UserSetting|null $settings
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TimeLog> $timeLogs
 * @property-read int|null $time_logs_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message> $unreadMessages
 * @method static \Illuminate\Database\Eloquent\Builder|User admins()
 * @method static \Illuminate\Database\Eloquent\Builder|User available()
 * @method static \Illuminate\Database\Eloquent\Builder|User byRole($role)
 * @method static \Illuminate\Database\Eloquent\Builder|User designers()
 * @method static \Illuminate\Database\Eloquent\Builder|User developers()
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User inProject($projectId)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User online()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User teamLeads()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCurrentTaskStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User working()
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string|null $full_name
 * @property string|null $phone
 * @property string|null $bio
 * @property string|null $language
 * @property string|null $theme
 * @property int|null $items_per_page
 * @property string|null $timezone
 * @property bool|null $email_notifications
 * @property bool|null $push_notifications
 * @property bool|null $task_reminders
 * @property bool|null $project_updates
 * @property bool|null $team_notifications
 * @property string|null $profile_visibility
 * @property bool|null $show_email
 * @property bool|null $show_activity
 * @property bool|null $show_online_status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting whereBio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting whereEmailNotifications($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting whereItemsPerPage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting whereProfileVisibility($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting whereProjectUpdates($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting wherePushNotifications($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting whereShowActivity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting whereShowEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting whereShowOnlineStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting whereTaskReminders($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting whereTeamNotifications($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting whereTheme($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting whereTimezone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting whereUserId($value)
 */
	class UserSetting extends \Eloquent {}
}

