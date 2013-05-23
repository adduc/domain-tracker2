Navigation
==========

IndexController
-----

### Action: Index - /
Landing page. If individual has session, move them to logged in page.


DashboardController
-----

### Action: Index - /dashboard
Logged in user's dashboard. Displays activity feed


DomainController
-----

### Action: Index - /domain
List followed domains, allow adding additional domains.

### Action: Add - /domain/add
Add Domain for tracking

### Action: View - /domain/view
View Domain details, history, etc.


UserController
-----

### Action: Index - /user - GET
List followed users

### Action: Follow - /user/follow - POST
Follow user

### Action: Unfollow - /user/unfollow - POST
Unfollow user


SettingController
-----

### Action: Index - /settings - GET
List account settings

### Action: Edit - /settings/edit - GET/POST
Edit account settings (visibility, password, etc.)
