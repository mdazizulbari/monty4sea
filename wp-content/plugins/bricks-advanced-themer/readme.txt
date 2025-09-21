=== Advanced Themer ===
Contributors: Maxime Beguin
Tags: bricks, bricks builder
Requires at least: 4.7
Tested up to: 10.0.0
Requires PHP: 5.6
License: GPL3
License URI: http://www.gnu.org/licenses/gpl-3.0.txt

Boost your Bricks websites. Build faster, easier, and better!

== Description ==

Advanced Themer levels up your efficiency in building websites with Bricks thanks to dozens of productivity hacks designed to facilitate your development process.

== Installation ==

1. Click on the download link in your purchase confirmation email if you have not already downloaded it after your purchase.

2. Download the plugin's zip file.

3. Go to Plugins > Add New in your WordPress admin. Click "Add New" button, then "Upload Plugin" button, then "Choose File", browse to and select the plugin's zip file.

4. Activate the plugin.

5. Enter the license key and activate your plugin license at Bricks → AT - License.

Valid license key should be entered for the plugin to function and to receive automatic updates.


== Changelog ==
= 2.8.1 ( AUG 28, 2024 )
* NEW: Link indicator inside the Structure Panel
* IMPROVE: Added a prefixed -webkit- fallback for the backdrop-filter property (needed for Safari users).
* IMPROVE: Revamped Plain Class modal with new Category dropdowns
* IMPROVE: New "Rename Mode" inside the Structure Helper
* IMPROVE: Added "Elements with at least one global class that contains Custom CSS" filter inside the Structure Helper Modal.
* IMPROVE: Added "Uncategorized" to the filter options of the groups inside the class manager > bulk actions.
* IMPROVE: The import CSS Variables function could generate long decimal values for clamps. Now it's limited to two decimals.
* IMPROVE: "Hide inactive Style accordion panel" is now compatible with BricksExtras and BricksForge.
* FIX: "Hide inactive Style accordion panel" could potentially hide content groups if some style groups were active.
* FIX: Bricks suggestion dropdown was hidden when the "Suggestions Dropdown for CSS Variables" was enabled.
* FIX: Importing clamp() variables with nested calc() functions would generate a JS error
* FIX: Auto-expanding the left panel inside the CSS tab wasn't working correctly.
* DEPRECATED: Resizable Structure Panel (introduced in Bricks 1.10.2)
* DEPRECATED: Parent Element Shortcut (introduced in Bricks 1.10.2)

= 2.8 ( AUG 13, 2024 )
* NEW: Admin/Editor notes
* NEW: Cleanup deleted global classes from the elements
* IMPROVE: Added GPT-4o and GPT-4o-mini to the AI models list
* IMPROVE: Added "em" in the list of the advanced wrapper tags
* IMPROVE: Added "Recent Search" inside the Dynamic Data modal
* IMPROVE: "Elements with more than one global class" added to the Structure Helper options
* IMPROVE: Removed auto-focus on SuperPowerCSS
* IMPROVE: Reduced lags when editing CSS using SuperPowerCSS on the Global Class level. 
* IMPROVE: Codemirror upgraded to version 5.65.16
* IMPROVE: The builder tweak "Highlight Parent Elements" is now Vanilla CSS only - No JavaScript
* IMPROVE: The highlight functions on the Structure Panel are now more performant
* FIX: Element shortcuts colors conflict since 1.10
* FIX: The Global class manager wasn't opening correctly when using keyboard shortcuts
* FIX: There was a conflict with the tweak “Auto-focus on the First Unlocked Class” and the Global elements of Bricks
* FIX: Importing color variables misses a final parenthesis in the variable value
* FIX: The "Expand structure" icon could move around when the AutoBEM feature of ACSS was enabled
* FIX: The code element wasn't fully scrollable since v1.10
* DEPRECATED: All the builder tweaks related to templates (now available in Bricks 1.10)


= 2.7.3 ( JUL 4, 2024 )
* FIX: Importing CSS variables inside the color manager was generating raw values with a missing final parenthesis
* FIX: Keyboard shortcuts were triggered inside the ACSS 3.0 panel
* FIX: Keyboard shortcuts were triggered inside the Struceezy panel
* FIX: Replaced the ACSS floating button when the right element shortcuts are enabled
* FIX: Floating structure panel wasn't working correctly with ACSS 3.0 installed

= 2.7.2 ( JUN 25, 2024 )
* IMPROVE: AT now includes blank index.php files in all folders to prevent directory listing of AT plugin folders for incorrectly configured web servers
* UPDATE: ACF PRO updated to version 6.3.2.1

= 2.7.1 ( JUN 24, 2024 )
* IMPROVE: Added an outline to the top shortcut icons when the related panel is active
* IMPROVE: The condition/interaction panels were blocking the pseudo-class panels when clicking on the corresponding shortcut
* FIX: The keyboard shortcuts for moving elements inside the structure panel weren't working correctly anymore
* FIX: Grid builder settings not getting saved on global Classes
* FIX: Duplicate Trailing slashes added to the nested elements
* FIX: JS error that could prevent some functions to mount correctly
* FIX: SuperPowerCSS wasn’t respecting the auto-expand settings

= 2.7 ( JUN 18, 2024 )
* NEW: Nested Elements
* IMPROVE: The search input is now automatically focused when opening the Dynamic Data Modal
* IMPROVE: Dynamic Data Modal filters now includes the parent group label of any tag
* IMPROVE: Dynamic Data key filter is now cleared each time you open the modal
* IMPROVE: Holding the Shift key while clicking on dynamic tag will prevent the modal to close
* IMPROVE: "Expand all children" builder tweak now works for collapsing items as well
* IMPROVE: Export AT settings now include the builder settings such as Nested Elements
* IMPROVE: Added the filter elements to the list of element you can enable/disable
* FIX: Dynamic Data Modal filters didn't work correctly with tag names
* FIX: Z-index issue with the class contextual menu since 1.9.9
* FIX: Dynamic items/icons inside Contextual menu of the structure panel weren't updated correctly since 1.9.9
* FIX: Shortcut icons colors were missing inside the left element panel since 1.9.9
* FIX: Updated the new topbar icon opacity values since 1.9.9
* FIX: Export settings inside the Theme Settings wasn't working correctly anymore
* FIX: "Move Children Up" builder tweak could reposition the children elements in the wrong order
* FIX: "Remove template links" in Strict Editor Mode wasn't working correctly anymore
* FIX: "Left panel visibility" element list has been refreshed to match the Bricks editable element list in Editor Mode
* DEPRECATED: "Open link in a new tab" is now part of Bricks Core.

= 2.6.4 ( MAY 31, 2024 )
* NEW: Dynamic Data Modal
* NEW: Image Mask Helper
* IMPROVE: New enqueue options for the CSS Theme variables.
* IMPROVE: New default enqueue position set to Head for CSS Theme variables.
* IMPROVE: Removed a server request when enqueuing CSS Theme variables.
* FIX: Custom CSS codes on the ID level weren't correctly removed with Class Converter
* FIX: Layout issue: style group labels could overlap the SuperPowerCSS control when adding long CSS declarations
* FIX: Content tabs could disappear when "Hide inactive accordion panels" tweak was active
* FIX: Dynamic Data dropdown hidden in popup controls
* FIX: Clicking on the Structure Panel header icons could wrongly trigger the draggable/resize function.
* FIX: CSS Variables Conversion script could lead to conversion failure.

= 2.6.3 ( MAY 17, 2024 )
* IMPROVE: New PHP filter to activate the Core Framework integration inside the Variable Picker
* IMPROVE: Resetting/importing/exporting global classes will also include the corresponding Global Class Categories
* FIX: The left content tab could return empty when clicking on the corresponding left shortcut
* FIX: Switching left tabs shortcuts under 100ms could calculate incorrectly the active Status
* FIX: The Form element could produce an empty style tab when switching from another element
* FIX: In some cases, importing CSS Global Variables could mess with the CSS Variable Categories array

= 2.6.2 ( MAY 16, 2024 )
* FIX: Imported variables with clamp functions and min/max values set to px weren't parsed correctly  
* FIX: Missing prefix during the theme variables conversion
* UPDATE: ACF PRO updated due to a Security Patch 

= 2.6.1 ( MAY 15, 2024 )
* IMPROVE: Added an error message inside AT Variable Manager when the Bricks Variable Manager is disabled inside the Bricks Settings.
* IMPROVE: In the variable manager, the pixel unit was an input and prevented a smooth TAB keyboard switch.
* FIX: Hide Element inside the builder wasn't applying for specific elements such as image and image galleries.
* FIX: When typing in a text control, the bricks variable suggestion dropdown was showing even if AT's suggestion dropdown was activated
* FIX: PHP warnings showing in some cases during the variable conversion

= 2.6 ( MAY 14, 2024 )
* NEW: Text-to-Speech
* NEW: Focus Point
* NEW: Hide/Remove Element
* NEW: Merge Global Classes
* IMPROVE: Revamped Variable Picker
* IMPROVE: New "Fullscreen" mode for the Variable Manager of AT
* IMPROVE: Assign new category to multiple items by drag & drop is now supported inside the Class Manager and the Variable Manager.
* IMPROVE: clamp-builder now supports internal tokens to modify the min/max vewport & the unit values globally.
* IMPROVE: New default toggle to enable Keyboard Shortcuts for creating elements by default on page load.
* IMPROVE: Popups such as Border or Box-Shadow won't close anymore when the user selects a CSS variable from the variable picker.
* IMPROVE: Added the new GPT-4-Turbo model.
* IMPROVE: Renamed "Save Post" button to "Save to Database" inside the Class Manager.
* IMPROVE: Global CSS variables are now editable inside the builder.
* IMPROVE: Global CSS variables are compatible in both the core variable manager and AT's variable manager.
* IMPROVE: Hide/Show element visibility inside the class contextual menu even if a global class is active.
* IMPROVE: Default Theme Settings now reflect the official recommended settings.
* IMPROVE: New option to force a specific default color scheme on the frontend.
* IMPROVE: New control visibility options in Strict Editor View.
* IMPROVE: Updated the delimiter from white space to comma when adding multiple class or variable categories in their corresponding managers.
* FIX: "Extend spacing controls" tweak is now compatible with the new core spacing controls introduced in 1.9.8.
* FIX: The top element shortcut icons could overlap with the class input.
* FIX: Commented code not saved in the database if no further keyup was triggered.
* FIX: "Hide the Dynamic Data trigger" in Strict Editor Mode wasn't including the dynamic icon inside Rich Text Elements.
* FIX: In Strict Editor View, the "revisions" tab wasn't visible anymore.
* FIX: The "left panel visibility" tweak in Strict Editor View was hiding "History", "Pages" or "Settings" permanently.
* FIX: Left tab shortcuts required a double click to switch panels when the CSS tab with SuperPowerCSS enabled was active.
* FIX: Modals were closing when releasing the mouse click outside of the modal itself, instead of firing on mousedown event only.
* FIX: Right-click of the mouse could trigger the Resizable function of the Structure Panel.
* FIX: Creating elements by keyboard shortcuts from the preview window could create a bug leading the element to disappear from the structure.
* FIX: A JavaScript error related to the Tag Manager could prevent the left tabs shortcuts from being correctly mounted.
* FIX: Expanded spacing controls compatibility with 1.9.8.
* FIX: Variable Picker icon compatibility with 1.9.8.
* FIX: The suggestion dropdown wasn't showing correctly in SuperPowerCSS when the editor is in fullscreen mode.
* FIX: "Hide inactive Style accordion panel" could generate an empty style tab.
* FIX: The structure panel was cropped on the bottom on Firefox.
* FIX: Fixed a layout issue on the right sidebar when the Admin Bar is enabled inside the builder.
* FIX: Responsive issues in the generated CSS of the grid builder when no settings are applied on children through different breakpoints.
* FIX: Error when dragging colors if any color in the palette is collapsed inside the Color Manager.
* FIX: The grid utility global classes weren't correctly removed in the builder when deactivating the CSS Classes/Grid utility classes tabs in the theme settings 
* DEPRECATED: Media Queries indicators.
* DEPRECATED: Global CSS variables categories in the backend. Now they are managed inside the builder.
* DEPRECATED: The "expand spacing" switch has been removed since it has been introduced natively in 1.9.8.
* DEPRECATED: ACSS & Core Framework integrations inside the Variable Picker.
* UPDATED: ACF PRO updated.

= 2.5.3 ( APR 4, 2024 )
* FIX: Bug when creating global classes using the Class Converter and Plain Classes.

= 2.5.2 ( APR 3, 2024 )
* IMPROVE: Global Classes extracted and saved from the Advanced CSS Panels are now locked by default
* IMPROVE: Custom CSS and SuperPower CSS controls are now full-height by default when "Hide inactive Style accordion panel" tweak is enabled
* IMPROVE: Added a toggle for enabling/disabling keyboard shortcuts for creating new elements
* IMPROVE: Creating nested elements through the right sidebar could eventually wrongly create siblings
* FIX: "Reduced Left panel visibility" in Strict Editor View was sometime not showing the panel correctly. 

= 2.5.1 ( Mar 29, 2024 )
* IMPROVE: Added SASS integration to the Custom CSS control inside the Global Class Manager
* FIX: Left tabs shorcuts weren't always mounted correctly when Focus On first class is enabled
* FIX: A JS error could popup when an element had a css class added, but no global class attached
* FIX: Increased compatibility with earlier versions of Bricks 1.9.7
* FIX: Changes in Strict Editor Settings weren't saved correctly in database
* FIX: SuperPowerCSS not showing changes when navigating through style tabs

= 2.5 ( Mar 27, 2024 )
* NEW: SASS integration for Advanced CSS & SuperPower CSS (Experimental)
* NEW: AT Main Menu in toolbar
* NEW: Contextual Class Menu
* NEW: Select the controls your clients can see for each element type (Strict Editor View)
* NEW: Copy/Paste/Reset all the global classes from one element to another
* NEW: Add a Featured Image Column in the Templates Admin Screen
* IMPROVE: Theme settings UX improved
* IMPROVE: Right Shortcuts for creating Elements now support Keyboard shortcuts
* IMPROVE: Left Tabs Shortcuts now support Keyboard shortcuts
* IMPROVE: Added an option to disable all the controls by default (Strict Editor View)
* IMPROVE: Builder Tweaks for Strict Editor View improved to quickly enhance the UX.
* IMPROVE: More flexibility added to the left panel visibility tweak (Strict Editor View).
* IMPROVE: Custom dummy content for Lorem Ipsum Generator.
* IMPROVE: Removed the "tab" conditional loading for Builder Tweaks. All the tweaks are visible by default.
* IMPROVE: Increase the input space for margin/padding controls when the variable picker is active
* IMPROVE: Gutenberg CSS declarations are now conditionally enqueued only if a blocks is detected in the page.
* IMPROVE: Advanced CSS has been moved inside Builder Tweak tab.
* IMPROVE: New keyboard shortcuts for Query Loop Manager, Find & Replace and Plain Classes.
* IMPROVE: Keyboard shortcuts are now compatible for WIN users.
* IMPROVE: Generate Global Classes from your Child Theme CSS (Advanced CSS).
* IMPROVE: Added "Divider" as element shortcut, and removed "Social Icons" & "Icon box".
* IMPROVE: Major Dynamic data compatibility in Global Query Manager
* IMPROVE: SuperPowerCSS now integrates a new Fullscreen mode
* IMPROVE: The shorcuts sheatsheet are now hidden by default in SuperPowerCSS
* IMPROVE: License Security enhancement
* IMPROVE: Moving elements through the contextual menu won't close the menu on click
* IMPROVE: Advanced Themer will now deactivate itself if Bricks isn't activated
* FIX: Gutenberg Colors could duplicate the styles loaded by AT on frontend.
* FIX: Gutenberg Colors weren't applying correctly for overlays
* FIX: Focus on class didn't work correctly when clicking on the preview window from the structure panel
* FIX: Remove data when the plugin was uninstalled wasn't working correctly
* FIX: Auto-select Import Images and/or Replace Content in the template popup weren't correctly unselectable in the builder.
* FIX: When using the search function inside the builder, SuperPowerCSS control was automatically focused and created bad UX
* FIX: Imported color shades wouldn't update when the parent color is modified
* FIX: The "Backdrop-filter" control wasn't showing correctly when the new FILTERS / TRANSITIONS tab wasn't enabled
* FIX: Vanilla CSS values and "!important" weren't suggested anymore in SuperPowerCSS
* FIX: The Draggable Structure Panel wasn't working correctly when the admin bar was enabled inside the builder.
* FIX: The Root component toggle for Class Converter was showing inside the Strict Editor View.
* FIX: Centered the "V" inside the variable picker toggle
* FIX: Class Preview on hover wasn't working when after filtering the classes
* FIX: Masonry view inside the template popup was breaking the layout of the unsplash popup
* FIX: Fixed a bug that prevented SuperPowerCSS to mount correctly in some cases
* FIX: Imported CSS files in Advanced CSS could generate an error with OpenSSL activated on the server
* FIX: The AI assistant panel in Advanced CSS was not visible anymore

= 2.4.2 ( Feb 9, 2024 )
* NEW: Masonry Layout for the template popup grid
* NEW: Auto-select Import Images and/or Replace Content inside the template popup
* IMPROVE: Plain classes icon is now visible even if a class is active
* IMPROVE: New attention messages when resetting global settings
* IMPROVE: Hide the class manager topbar icon in Strict Editor View
* IMPROVE: New "Comment" icon inside SuperPowerCSS
* FIX: Quick-save template not working an certain template types
* FIX: Resolved a JS error that caused SuperpowerCSS to fail mounting correctly
* FIX: Fixed a warning "Undefined array key 'query_manager' in builder.php"
* FIX: AT Theme Settings menu item was visible to Editors in Strict Editor View
* FIX: Text Link elements weren't selectable in Strict Editor View
* DEPRECATED: Drag-and-drop Classes (now integrated in core)

= 2.4.1 ( Feb 2, 2024 )
* FIX: Error converting AT's Class Categories into compatible Bricks ones
* FIX: Box-shadow Generator & Grid Builder settings wouldn't apply correctly on pseudo-elements
* FIX: Locked classes becoming wrongly unlocked

= 2.4 ( Jan 31, 2024 ) 
* NEW: Box-shadow Generator
* NEW: Global Query Manager
* NEW: Quick-save Templates
* NEW: Advanced Text Wrapper
* NEW: Copy/paste your Interaction & Condition settings
* NEW: Speech to Text converter
* NEW: Move Styles over different elements, classes or breakpoints
* NEW: More used classes suggestion & group filter
* NEW: ID’s overview
* NEW: Global Query Loop Dropdown
* NEW: Collapse/Expand the Elements list
* GRID BUILDER V2: Repeat Grid
* GRID BUILDER V2: New Span switch
* GRID BUILDER V2: Fill empty cells inside the grid
* GRID BUILDER V2: Use minmax() for your grid-template values
* GRID BUILDER V2: Replace Class/Query loop CSS automatically 
* GRID BUILDER V2: Add/Remove cells
* GRID BUILDER V2: Resize cells from all 4 corners
* GRID BUILDER V2: Clear View from the cell
* GRID BUILDER V2: Cell shortcuts
* GRID BUILDER V2: Generate Bento Grid
* GRID BUILDER V2: Bug Fixes and UX improvements
* Minor Improvements to Style Overview
* CLASS CONVERTER: Support for nested Class Components
* CLASS CONVERTER: Update classes
* CLASS CONVERTER: Group Suggestion
* CLASS CONVERTER: Minor UX improvements
* CLASS MANAGER: New Bulk Actions
* CLASS MANAGER: Enable/Disable specific classes in the bulk actions
* CLASS MANAGER: New “Ungrouped” Category
* CLASS MANAGER: Multiple keywords filter
* CLASS MANAGER: Synched Categories between Bricks and AT
* CLASS MANAGER: Minor UX improvements and Bug fixes.
* FIND & REPLACE: Update values inside global classes
* STRUCTURE HELPER: New icons for the previewed structure
* STRUCTURE HELPER: Show interactions set on global classes
* ELEMENTS SHORTCUTS: New Text-link icon
* INDICATORS OF STYLES INHERITED FROM A CLASS: Group Tabs indicators
* INDICATORS OF STYLES INHERITED FROM A CLASS: Different indicators for ID and classes
* INDICATORS OF STYLES INHERITED FROM A CLASS: Indicators between Classes
* RESOURCE PANEL: New zoom slider
* RESOURCE PANEL: UX Improvements
* RESOURCE PANEL: Bug Fixes
* GRID GUIDES: In some cases the grid guides weren’t covering the entire top/bottom screen.
* IMPORT/EXPORT: Overwrite existing settings
* PLAIN CLASSES: Minor UX improvements and bug fixes
* DEPRECATED: FRONTEND PLAYGROUND


= 2.3 ( Dec 15, 2023 ) 
* NEW: CSS Grid Builder
* IMPROVE: Bricks colors compatibility improved in Gutenberg
* FIX: Unnecessary padding on spacing controls
* FIX: Structure panel wasn't dragging without the elements shortcuts

= 2.2.1 ( Nov 28, 2023 ) 
* NEW: Style Overview Shortcut
* NEW: Export option in Class Manager
* NEW: “Lock ID Styles” for elements with Global Classes
* IMPROVE: Expand Spacing Controls improved
* IMPROVE: Draggable Structure Panel Improved
* IMPROVE: Component Class Manager improved
* IMPROVE: Responsive Helper improved + bug fix
* IMPROVE: Tabs Shortcut improved

= 2.2 ( Nov 20, 2023 ) 
* NEW: Responsive Helper
* NEW: Enable / Disable any Bricks element
* NEW: Draggable Structure Panel
* IMPROVE: Plain Classes improved
* IMPROVE: Preview Classes on Hover has been improved
* IMPROVE: Bulk duplicate classes improved
* IMPROVE: Strict Editor View improved
* IMPROVE: Variable Manager enhancement
* IMPROVE: Import/Export/Reset settings improved
* IMPROVE: Grid guides revamped
* IMPROVE/FIX: SuperPower CSS: bug fixes and enhancements
* FIX: Fatal Error “Uncaught Error: Class “Bricks\Elements” not found”
* FIX: Wrong base font-size calculated inside the builder
* FIX: The structure helper isn’t showing correctly on template pages
* FIX: Error with imported elements generated by Nimbufy
* FIX: Imported Classes not previewing inside the Builder
* FIX: Duplicate Color Palette bug
* FIX: Error with Tab shortcut & Lock ID styles

= 2.1.1 ( Oct 31, 2023 ) =
* FIX: Global base Font-size	
* FIX: Forced Class Preview on hover	
* FIX: The CSS variables not showing in the Strict Editor Mode	
* FIX: Unclickable buttons in the Variable Picker

= 2.1 ( Oct 30, 2023 ) =
* NEW: Variable Manager v1
* NEW: Complementary Color Generator
* NEW: Auto-darkmode
* NEW: Import colors from a CSS variable List
* NEW: Enable/Disable any Color Palette
* NEW: Structure Panel Helper
* NEW: CSS keyboard Shortcuts
* NEW: Class Manager Shorcut
* NEW: Element CSS Shorcut
* NEW: Expand Spacing Controls
* IMPROVE: Advanced CSS improved
* IMPROVE: Variable Picker improved
* IMPROVE: New “full-size” option in Superpower CSS
* IMPROVE: Comments added to Superpower CSS
* IMPROVE: Description added to the Class Manager
* ... and tons of small bug fixes and UX enhancements

= 2.0.4 ( Sep 28, 2023 ) =
* IMPROVE: Superpower CSS improved	
* IMPROVE: Style Overview improved
* IMPROVE: Import ID styles to classes improved
* FIX: Duplicated prefix in the color variables


= 2.0.3 ( Sep 27, 2023 ) =
* IMPROVE: Darkmode toggle and button improved	
* IMPROVE: “Active color on page” indicator improved	
* IMPROVE: Suggestion dropdown inside the Superpower CSS improved
* FIX: Color variables not visible inside the builder


= 2.0.2 ( Sep 26, 2023 ) =
* IMPROVE: Duplicated CSS variable suggestions in the Superpower CSS dropdown
* IMPROVE: Color variables were missing inside the suggestion dropdowns
* IMPROVE: The data-theme attribute is now toggling correctly inside the builder
* IMPROVE: “Focus on first unlocked class” improved
* FIX: “Disable auto-expand” wasn’t respected in Superpower CSS
* FIX: Blank screen errors for editors using the Strict Editor Mode	

= 2.0.1 ( Sep 22, 2023 ) =
* FIX: Error opening the Advanced CSS modal when no CSS is set on the current page
* FIX: Minor CSS fixes inside the builder (light theme)

= 2.0 ( Sep 21, 2023 ) =
* NEW: Group your Classes by Categories
* NEW: Generate CSS with AI
* NEW: Bulk Actions on Classes
* NEW: Convert Vanilla Bricks Colors into CSS variables
* NEW: Manage & Organize Your Colors with Ease
* NEW: Advanced Color Pickr
* NEW: Alpha channel supported
* NEW: Color Shades generator
* NEW: Advanced Scale Generator
* NEW: No-code Darkmode
* NEW: Customize any shade and dark color variant
* NEW: Set Custom Darkmode colors for ACSS variables
* NEW: Expand All Children Elements
* NEW: More CSS Controls
* NEW: Meta Theme Color – Both globally and per page 
* IMPROVE: Copy Color Variable to Clipboard in the Color Manager
* IMPROVE: Active Colors on page indicator inside the Color Manager	
* IMPROVE: Multi-select drag & drop in the Color Manager
* IMPROVE: Improved Default Palettes inside the Color Manager
* IMPROVE: Add you custom Icons inside the Light/Dark mode toggle/button
* IMPROVE: Added a toggle to enable/disable the dark mode color variables on frontend
* IMPROVE: Enhanced the Light/Dark mode to avoid FUC on load
* IMPROVE: Dark mode now supports the browser option "prefers-color-scheme"
* IMPROVE: Multi-select drag & drop inside the Class Manager
* IMPROVE: Copy Class to Clipboard inside the Class Manager
* IMPROVE: Manually Rename Classes for Bulk Actions inside the Class Manager
* IMPROVE: Reset button added to the Bulk Actions tab inside the Class Manager
* IMPROVE: More Granular Control over the classes created with the Class Converter	
* IMPROVE: Manually Rename Classes inside the Class Converter	
* IMPROVE: Improved Import/Export Styles to Classes when using Mobile-first Breakpoints
* IMPROVE: Added an option to import the Variable Framework from the database	
* IMPROVE: Improved Custom Theme compatibility inside the builder
* IMPROVE: The AI responses are now wrapped in a code block
* IMPROVE: Set the default AI model and tones of voice in the AI settings
* IMPROVE: Personalize the CSS generated for the Strict Editor View
* IMPROVE: Set the Overflow control as a dropdown
* IMPROVE: The superpower css has been improved
* IMPROVE: New Classes / ID Tab
* IMPROVE: Darkmode supports “prefers-color-scheme” settings
* IMPROVE: Autoformat works inside the theme styles
* IMPROVE: “body.brxc-dark” has been retired
* FIX: Color picker now accepts HEXA capitalized letters
* FIX: Adapted all the CodeMirror instances to support %root%
* FIX: Fixed a bug when the custom CSS selectors weren’t duplicated correctly when using the Class Manager
* FIX: Tag manager is working again
* FIX: Change element ID even if “Auto-focus on the First Unlocked Class” is enabled
* FIX: Improved compatibility with Mobile-first breakpoints
* ...and tons of small bug fixes.

= 1.3 ( Aug 18, 2023 ) =
* Class Manager
* Component Class Manager
* Drag-n-drop your element’s classes
* Auto-select Import Images in the Template popup	
* Added an option to transform the Heading input into a multiline textarea 
* Builder Light mode enhancement
* Custom HTML tags are now showing in the structure panel	
* Added an option to set “SVG” as default library for Icons elements	
* Enhanced Firefox compatibility	
* Class converter didn’t parse multiple “root” in custom css
* New shortcut elements
* 1-column layout to the elements list	
* Lorem/AI icons visible inside the class dropdown of the header element	
* Superpower CSS wasn’t mounted correctly when switching breakpoints	
* The element shortcut sidebar wasn’t sticky anymore	
* :before and :after styles not applied correctly	
* Typing “!” wasn’t triggering the suggestion dropdown in Superpower CSS	
* CSS doesn’t always apply inside Superpower CSS

= 1.2.4.2 ( Aug 10, 2023 ) =
* Autoformat applies now only on fields that accept a css value
* The clamp() function returned incorrect "middle" values
* The clamp() function has been shortened to max 3 float digits
* Import styles from ID returned a JS error on object values
* CSS suggestion dropdown was incorrectly triggered by the open bracket in Superpower CSS
* Performance boost

= 1.2.4.1 ( Aug 9, 2023 ) =
* Autoformat your control values with advanced CSS functions
* Basic text / Rich text / Heading converter
* Variable preview on hover was triggered even if the mouse was not moving
* ACF flexible content default text was modified by AT	
* Lorem ipsum generator and AI icons not showing on headers	
* Superpower CSS Performance issues
* Tag Manager issues in Firefox
* Buttons in Advanced CSS not working correctly in Firefox	
* Autocompletion issue on foreign keyboards	
* Keyboard shortcuts not working on Windows	
* Expand classes issues	
* History not working correctly in the Builder	
* Element Shortcuts not working on Template Section

= 1.2.4 ( Aug 4, 2023 ) =
* Group classes by Lock Status	
* Save your Global CSS from the builder	
* Import your Custom CSS from each element to your Global CSS
* Autogenerate your CSS declarations	
* Extract your classes from the Advanced CSS and save them as Bricks Global Classes
* Suggestion dropdown incorrectly triggered on German keyboards	
* Element shortcut not working on Archive pages	
* ACF has been upgraded	
* Indicators functions have been refactored
* Empty Accordion Panels in the Style Tab	
* Styles in the Content Tab are now hidden when the “Lock ID styles” feature is enabled
* Compatibility issue with WP Grid Builder	
* Style Overview reporting wrong values on mobile-first breakpoints since 1.8.4	
* The “CSS variables” tab inside the Advanced CSS panel has been deprecated	
* The imported CSS files weren’t showing correctly inside the Advanced CSS panel	
* Autoclose variable brackets didn’t support the !important tag
* Indicators not showing correctly when “autofocus on first unlocked class” is enabled	
* Click outside the Structure Panel didn’t unselect the active element
* Tag Manager improved	
* JS error in the Structure Panel
* Focus on the container when adding a section through shortcuts improved
* Export/Import ID Styles to Classes improved	
* Extend Classes improved	
* Remove a white space in the Structure Panel

= 1.2.3 ( Jul 20, 2023 ) =
* Custom default settings for relevant elements
* Reveal the “class icons” on hover and focus	
* Increase the builder’s inputs size to easily manage CSS variables and custom CSS functions
* Auto-close CSS Variable Brackets
* Review the CSS generated by Bricks for each Element and Classes	
* Autoselect the first unlocked class when browsing elements
* GPT-4 is now the default AI model	
* Style Overview is not experimental anymore
* The container element gets autofocused when adding a section through the “section” shortcut	
* The “Section” shortcut is now added to the Structure Panel Root
* The “Section” shortcut now includes a nested Container by default
* Adding a new element from the shortcut panel will expand the parent inside the structure panel
* Default Structure Panel Size
* CSS property suggestions now include “: ” when autocompleted inside the Superpower CSS
* Added padding-top to the “create element shortcuts” panel	
* CSS values suggestions have been improved in the Superpower CSS	
* Moving items in the structure panel was conflicting with the navigation shortcuts
* Dropdown removed in the “overview” mode of the Tag Manager
* Pseudo indicator not working correctly for classes	
* Missing closing bracket in some Core Framework variable values	
* “!important” suggestion created a double “!!” in Superpower CSS.
* Z-index issue with the Tag Manager Dropdown

= 1.2.2 ( Jul 10, 2023 ) =
* Added the Core Framework integration inside the Variable Pickr
* Fixed Style & Class indicators stopped working since the 1.8.2 upgrade
* Fixed the "Superpower CSS control" was not saving the different breakpoint correctly
* Fixed the suggestion dropdown was triggered by the delete key and the control key inside the "Superpower control"
* Fixed a duplicated tooltip in the theme settings for the Parent Icon builder tweak
* Fixed an issue where the shift + arrows were triggering the Move Elements tweak when the mouse was outside of the structure panel
* Fixed a CSS compatibility issue the Loader Plus plugin
* Fixed an issue where AT wasn't loading the proper version of ACF PRO if the plugin was already installed by the user
* Fixed Style Overview was loaded even if the feature was disabled in the theme settings

= 1.2.1 ( Jul 1, 2023 ) =
* Fixed Style Overview not displaying correctly on Firefox Dev edition
* Fixed the property filter inside Style Overview wasn’t displaying correctly after a 0 result search.
* Fixed Global classes weren’t updated correctly when the Superpower Custom CSS feature was enabled
* Fixed multiple “root” items weren’t parsed correctly inside the code editor controls when the Superpower Custom CSS feature was enabled
* Added the "Non standard" CSS properties to the suggestion dropdown inside the superpowered Custom CSS control
* Fixed changes to a cloned class were affecting the original class too.

= 1.2 ( Jun 29, 2023 ) =
* New Experimental “Style Overview” function inside the structure panel	
* Superpower the Custom CSS control
* Autocomplete CSS Variables Suggestions on Hover is now a separate option in the theme settings.
* Various Bug Fixes


= 1.1 ( Jun 9, 2023 ) =
* New “move element” function inside the structure panel	
* New “Delete Wrappers & Move Children Up” function	
* The “Hide element” function is now dynamic	
* New “Highlight Nested Elements” function in the structure panel 
* New “Highlight Parent Elements” function in the structure panel
* Preview the CSS variables from the autocomplete suggestion dropdown
* Autofocus on the AT’s input fields
* Save input fields with keyboard shortcuts
* Bug fixes


= 1.0.9.1 ( May 30, 2023 ) =
* New "Hide inactive Style accordion panel" function
* Tabs Shortcuts Top Offset
* Added "Shape Dividers" in the Tabs Shortcuts list
* Improved RTL support
* Improved Light Theme compatibility
* Bug fixes


= 1.0.9 ( May 24, 2023 ) =
* The builder tweaks scripts have been refactored
* New “structure panel” tab inside the builder tweaks options
* New “class convertor” feature
* New “elements tag” feature
* New “Breakpoint Indicator” feature
* New “Indicators of styles inherited from a class” feature
* New “Style Indicators in the media queries panel” feature
* New “Locked Class Indicator” updates
* New “copy class to clipboard” feature
* New “clone class” feature	
* New “hide element” feature
* New “class highlight” indicator in the structure panel
* New options in the “extend classes & styles” features
* “Disable styling on ID level” feature has been revamped
* Tons of bug fixes

= 1.0.8.1 ( May 11, 2023 ) =
* Fixed elements not collapsing in the structure panel
* Fixed indicators not showing on the right of the elements inside the structure panel
* Fixed the last element in the structure panel was partially hidden
* Fixed skipped conditions, interactions, attributes, css & class fields (inside the style tab) from triggering the "style on ID" indicator inside the structure panel
* Fixed containers elements weren't nesting elements from the structure panel shortcuts when belonging to the root of the structure
* Fixed a backend design issue on Safari
* Fixed the resize bar inside the structure panel that was overlapping with the expand elements toggle
* Added the class field from the style tab as a trigger for the Class indicator inside the structure panel
* Added filters to customize classes and css variables inside AT

= 1.0.8 ( May 10, 2023 ) =
* New indicators for the left panel and the pseudo elements shortcuts	
* Added the “View page on new tab” Icon
* Fixed various small bugs and errors	
* Borders, Box Shadow, and Width were added as CSS variables	
* The Theme Settings have a new look and functionalities	
* Style & classes indicators inside the structure panel
* Optional enqueue for your imported class	
* Display issue on fields with dynamic icon within the query loop popup
* ACF PRO updated 	
* Error when using acf_form()
* Added A backward compatibility for the clamp() functions	
* Autofocus when adding an element from the sidebar


= 1.0.7 ( May 3, 2023 ) =
* New right sidebar shortcuts
* Resizable Structure Panel
* BricksLabs Integration
* Performance enhancement on Frontend
* Escape key close modals
* Fixed “Disable PIN icon on element list” feature
* Error when deactivating the AT license
* ACSS variables blocked by the server	
* Wrong active state of pseudo-element shortcut
* Update the root value when exporting styles to a Class
* Skip Id and Classes fields when exporting styles to a Class
* Extend Class and Styles not working correctly
* AT License key is now hidden on frontend
* Fixed the Resource Panel

= 1.0.6 ( Apr 28, 2023 ) =
* Added a tooltip including the values of AT CSS variables
* The “class preview on hover” stopped working	
* Moved the color palette selector on top of the popup
* The “import styles from Element ID” function not working 
* Variables not showing inside the Advanced CSS Panel
* OpenAI API key not saving
* Fixed the grid and imported classes on frontend

= 1.0.5 ( Apr 27, 2023 ) =
* New Backend UI for the Theme Settings
* Reset your Theme Settings
* Import/Export your Theme Settings
* Updated the default Typography/Spacing fluid scale
* Resize the AT popups inside the builder
* Import your own CSS Variable Framework
* Allow upload of JSON and CSS files in the Media Library
* Variable Preview on hover
* ACSS variables support for the Variable Picker
* Customize the tabs inside the Advanced CSS Panel
* Solved a plugin conflict related to the license Management
* Fixed a Grid Color design issue on Safari
* Enhanced Firefox support

= 1.0.4 ( Apr 22, 2023 ) =
* The color palette CPT wasn’t loaded by default
* Fixed a bug that prevented updating the color palettes
* The grid classes weren’t loading on frontend 

= 1.0.3 ( Apr 21, 2023 ) =
* min/max values now support 2 decimals values	
* Fixed the dashboard menu order
* Enhanced the Advanced CSS panel	
* Default Column Settings for the elements list
* Enhanced the backend options of the Strict Editor view
* The clamp functions now support the CQI unit
* Fixed a bug with the Extend Global Classes feature
* Fixed the links inside the AT Panels	
* New UI for builder tweaks category	
* Fixed a Javascript with Plain Classes
* Added enqueue options inside the Class Importer
* Fixed the X-mode not showing in the header/footer	
* Fixed the ugly scrollbars on firefox/safari	
* Fixed the class lock issue on reload	
* Fixed wrong URL’s inside the admin bar items when the installed folder isn’t the root
* Enable/Disable any function in AT	
* ACF menu wasn’t showing in Dashboard when ACF Pro was enabled

= 1.0.2 ( Apr 17, 2023 ) =
* Minor improvements and fixes
* The Delete image icon disappeared
* Plaster compatibility CSS fix for the color grid
* “Disable borders and box-shadow” feature wasn’t working correctly on v1.7.3
* Grid classes & imported classes weren’t updated correctly in the builder
* Fixed Plain Classes not deleting the classes correctly
* Fixed Critical error

= 1.0.1 ( Apr 12, 2023 ) =
* Fixed various bugs

= 1.0.0 ( Apr 9, 2023 ) =
* Initial release*  
