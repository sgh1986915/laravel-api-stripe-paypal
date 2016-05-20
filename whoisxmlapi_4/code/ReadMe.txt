All scripts have the same "Fill in your details" section near the top asking for a username, password, and domain.  These need to be provided in each script in order to work.

PHP:
	Nothing extra should be required.  Just put it in the hosting environment and go.  It is meant for web hosting as it returns HTML, not the command line.

Javascript:
	Just open the page and go.  Nothing extra required.

Python:
	I used the latest Python, 3.2.  If you have this verion nothing extra is required.

Ruby:
	I used the latest version, 1.9.3.  If you have this verion, nothing extra is required.

C# .NET:
	If you have Visual Studio 2010 and you open the provided .sln file, everything is already set up.  Otherwise (this is repeated in the whois.cs file as well):
	Note that you need to make sure your Project is set to ".NET Framework 4" and NOT ".NET Framework 4 Client Profile"
	Once that is set, make sure the following references are present under the References tree under the project:  Microsoft.CSharp, System, System.Web.Extensions, and System.XML