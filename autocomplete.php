<html>
<head>
  <meta charset="utf-8">
  <title>autocomplete demo</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
</head>
<body>
 
    <input id="autocomplete" name="skill">
 
<script>
$( "#autocomplete*" ).autocomplete({
  source: [ "Excel","Word","Power Point","Microsoft Visio","Microsoft Project","UML","Web Sphere","JSE","JEE","XML","CSS","HTML","JSON","Python","Objective-C","Swift","C#","Django","Google API","Facebook API","Twitter API","Polymer","Unity 3D","Vuforia","Metaio","AR Tool Kit","NodeJS","Spring","Hibernate","Ibatis","Ajax","AngularJS","JSF","Oracle","Postgres MySQL","MongoDB","Maven","Tomcat","Camel","Ant","JMeter","JBoss Application Server","Datagrid","Openshift","JBoss Fuse Service Works","JBoss BPM Suite","OpenSource","Mayan EDMS","Solaris","iOS","Junit","Grinder","LoadRunner","Findbugs","Checkstyle","Agitator","Cactus","Sonar","Weblogic Application Server","Oracle Business Rules","Oracle BPEL","Oracle Business-to-Business Integration","Oracle Business Activity Monitoring","Oracle Service Bus","Oracle Complex Event Processing","Oracle Enterprise Gateway","Oracle Data Integrator ODI","Oracle SOA Suite","Coherence","XP","UP","Scrum","Ui/Ux","TDD","DDD","OOD","Design Patterns","Architectural Enterprise Patterns","GRASP","ITIL","TOGAF","Autodesk Maya","Photoshop","Dreamweaver","CA Introscope","CA CEM","Foglite","Dynatrace","C++", "Java", "Php", "Coldfusion", "Javascript", "Asp", "Ruby", "Windows", "Linux","Unix", "JQuery", "SQL", "MySQL" ]
});
</script>
 
</body>
</html>