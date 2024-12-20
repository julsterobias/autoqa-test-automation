# Ver 0.9.8.3 Beta

# AutoQA
Automate the testing of your WordPress sites and plugins without the need for coding.

AutoQA is a simple automation plugin designed to improve your regression testing process, reducing testing time by up to 70%. It comes equipped with a variety of useful testing steps and a WordPress-specific test runner, ensuring seamless integration and efficient performance within your WordPress environment.

[Website](https://julsterobias.github.io/autoqa/) | [Documentation](https://julsterobias.github.io/autoqa/documentation)

## Flows
Each flow acts as a customizable test case, allowing users to simulate and verify various processes within their application. A flow consists of a series of steps, which represent specific actions or interactions. Whether it's a simple user journey or a complex workflow, this feature enables detailed testing by breaking down tasks into manageable steps, ensuring that every aspect of your process works seamlessly from start to finish.

## Steps
The Steps feature in the Flow plugin is designed to automate user actions, emulating how a real user would interact with your website or WordPress application. Each step represents a specific action, such as clicking a button, filling out a form, or navigating through pages. These steps are built within a Flow, where they combine to form a comprehensive test case.

By using Steps, you can easily automate repetitive user interactions, allowing the plugin to test your site or app seamlessly. This saves you time and ensures that all key functionalities are thoroughly verified without manual intervention.

With Steps, you can ensure a smoother, error-free experience for your users, all while simplifying your testing process.

## Runners
Designed to efficiently handle and execute each step of your flow in sequence. It ensures that all steps are processed systematically, checking whether each step passes or fails based on predefined criteria. The Runner automatically records the results, including pass/fail status, directly into the database for easy tracking and review. This feature simplifies the management of test cases, making it easier to monitor progress and identify issues at any step in the process.

## Steps
- Start - This step initiates the test by directing the browser or test framework to navigate to a specific URL provided by the user.
## Input
- Click - Simulates a user clicking on an element (e.g., a button or a link) on the web page.
- Hover - Simulates a user hovering their mouse over a particular element on the webpage.
- Manual Input - Provides an opportunity for the user to input values or perform actions manually during the test run.
- Set Text - Automatically fills in a text field or text area with specified input.
- Set Select - Simulates selecting an option from a dropdown or selection field.
- Send Keys - Simulates the sending keys to field.
- Drag and Drop - Simulates the drag and drop event.
- Empty Field - Empty the text field.
- Scroll - Emulates the action of scrolling through a page or a specific element.
- Reload - Simulates refreshing or reloading the current web page.
## Upload File
- Upload Image - Generates test image and simulate the upload event.
- Upload PDF - Generates test PDF and simulate the upload event.
## Check
- Check Page Title - Verifies that the title of the current webpage matches the expected value.
- Check Text - Verifies that specific text is present within a non-input field element, such as a <div>, <span>, or <p>.
- Check Value - Validates the value of form field elements like input, textarea, or select.
- Check Attribute - Validates that a specific attribute of an element has the expected value.
- Check Element Count - Verifies the number of occurrences of certain elements (e.g., li, div, option) on the page matches the expected count.
- Check Visibility - Checks if a specified element is visible or hidden on the page.
- Check Data - Validates data stored in the browser’s local or session storage, using a user-assigned name and value.
## Delay
- Delay - Pauses the execution of the test for a specified amount of time (in seconds).
- Wait To - Pauses the test execution until a specific element performs a certain action (e.g., becomes visible, clickable, or contains text).
# Data
- Element Data - Stores the value or attribute of an element into a data variable for later use.
- Store Data - Allows users to store dynamic data into a variable, which can include system-generated values like date and timestamp, or data extracted from elements.
## Wordpress
- Check Meta
- Check Transient
- Check Scheduler
- Check Post