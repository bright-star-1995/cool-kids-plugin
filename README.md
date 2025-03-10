# Cool Kids Plugin
Welcome to the Cool Kids Network.
This is a wordpress plugin that offers cool kids network features.

# The problem to be solved
This WordPress plugin aims to facilitate a fun and engaging platform where users can register, create characters, and interact based on their roles as "Cooler Kid" or "Coolest Kid," each with different levels of access to character data.

# A technical specification of design, explaining how it works.
- User Registration and Character Creation:
Users can register on the platform, which triggers the automatic creation of a character for them.

- User Access to Character Data:
Logged-in users can view their own character data.

- Role-Based Data Access:
Users with the "Cooler Kid" role can access other characters' data excluding email and role information.
Users with the "Coolest Kid" role can access all character data, including email and role information.

- API for Role Assignment:
Special roles like "Cooler Kid" and "Coolest Kid" can be assigned to users programmatically via an API.

# The technical decisions made and why.
1. MVC (Model-View-Controller) OOP Development:
- Utilizing the MVC design pattern in Object-Oriented Programming (OOP) for this WordPress plugin provides a structured approach to development, separating concerns for improved code organization and maintainability.
- Model: Represents the member data
Character data and user roles are managed within the model component. This encapsulation ensures a clear distinction between data handling and presentation logic.
- View: Handles the presentation layer.
Templates for displaying character information, user profiles, and data based on the user's role are structured within the view component, including signup and login pages. This separation facilitates easy customization of the plugin's UI elements.
- Controller: Acts as an intermediary between the model and view.
Implementation: The controller component orchestrates user interactions, processes requests, and manipulates data flow between the model and view. It enforces role-based access controls and governs the assignment of special roles using the API.

2. Plugin-based Development:
   This project was strategically developed as a WordPress plugin to leverage the myriad benefits that such plugins offer, empowering website owners to seamlessly enhance their site's functionalities without the necessity of bespoke coding from the ground up. This versatile plugin is designed to seamlessly integrate into any WordPress project, facilitating the seamless implementation of the innovative Cool Kids Network.

# How this solution achieves the admin’s desired outcome per the user story.
The plugin's features align with the admin's desired outcome by:
- Enabling seamless user registration and character creation via randomuser.me api.
- Granting different levels of access based on user roles.
- Ensuring that users with special roles can view character data as specified (excluding or including email and role information).
- Facilitating role assignment through an API to simplify administrative tasks.


# How to Install
Follow these steps to install the project on a WordPress site as a plugin:
1. Download the Plugin:<BR>
Obtain the plugin ZIP file containing all necessary plugin files.
2. WordPress Admin Dashboard:<BR>
In the WordPress Admin Dashboard, navigate to the Plugins section.
3. Upload Plugin:<BR>
Click on the "Add New" button, then select "Upload Plugin" to upload the ZIP file. Alternatively, you can extract the plugin folder to the wp-content/plugins/ directory directly.
4. Activate the Plugin:<BR>
After uploading, activate the plugin from the Plugins menu in the WordPress Dashboard.
5.Testing:<BR>
Test the plugin thoroughly to ensure all features work correctly within your WordPress environment.

# How to Run
1. Homepage<BR>
- Landing Page for Members
For verified members, a logout option is available on the top bar.
Display of individual character data and collective data for all members is accessible (specifically for roles: "cooler-kid" and "coolest-kid").
- Entry for New Users
For visitors not logged in, the top bar provides links to login and sign up.

2. Login Page<BR>
Users can log in using their registered email.
Successful login is granted upon verification; if not registered, access will be denied.

3. Signup Page<BR>
New users can create an account.
Account creation fails if the user is already registered. Otherwise, a new member is registered along with a character, generated from the randomuser.me API.

4. Wordpress Admin Panel<BR>
Located in the WordPress Admin section, there is a designated "CoolKids Members" menu on the sidebar.
Within this menu, administrators can update the roles of all members.
Role updates are facilitated through a REST API endpoint:
https://yourdomain.com/wp-json/api/v1/update-role
This API enforces administrator authentication before processing role updates.
This refined guide aims to ensure a clear understanding of navigating and utilizing the platform's functionalities in a professional manner.
