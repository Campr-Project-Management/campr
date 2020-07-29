![](https://user-images.githubusercontent.com/63307779/81802868-240f7280-9517-11ea-92a6-546ac97988fe.png)

CAMPR is an enterprise Project Management solution scalable for projects of any size to cover your individual needs.

CAMPR is lean, intuitively designed and offers everything from project initiation trough close out and allows you collaborate successfully with other project members following industry standards.

CAMPR comes with 16 fundamental modules to plan and successfully execute projects of any size while providing information in real time. Since all projects are unique by definition, the project creation wizard activates a lean bundle of certain modules only to ensure absolute efficiency.

Modules available:
* Project Contract
* Organisation
* Phases & Milestones
* Task Management
* Internal & External Costs
* Gantt Chart
* WBS
* Risks & Opportunities
* RASCI Matrix
* Meeting Reports
* Status Reports
* Decisions
* Todos
* Infos
* Close Out Report

# Getting started
* For the cloudversion just register here: https://campr.biz

# On-Premise Installation
* The On-Premise version is documented in the repo https://github.com/CamprGmbH/on-premise

# Structure
Use the following pages to find more about the structure of this project:

* [Admin Controllers](backend/src/AppBundle/Resources/docs/AdminControllers.md)
* [API Controllers](backend/src/AppBundle/Resources/docs/ApiControllers.md)
* [Entities](backend/src/AppBundle/Resources/docs/Entities.md)
* [Forms](backend/src/AppBundle/Resources/docs/Forms.md)
* [Services](backend/src/AppBundle/Resources/docs/Services.md)
* [Javascripts](backend/src/AppBundle/Resources/docs/Javascripts.md)

# Need help?
The easiest, fastest and most direct way to contact us is via our customer support software Livezilla (LiveChat, Email) on https://campr.biz

In addition, we use a Telegram channel for discussion and exchange.https://t.me/officialCAMPR

Within the tool you can give us feedback via the feedback button.

# Want to contribute?
If you want to contribute through code or documentation, please check out our [Contributing guide](https://github.com/CamprGmbH/campr/blob/develop/CONTRIBUTING.md).


# License
CAMPR is licensed under the [GNU](https://www.gnu.org/licenses/agpl-3.0.de.html) Affero General Public License v3.0

# Acknowledgements
Thanks to [Christoph Pohl](https://github.com/orgs/CamprGmbH/people/cristobalcampr) and [Manuel Weiler](https://github.com/orgs/CamprGmbH/people/CAMPR-Manuel) for creating and sharing this project with the open source community.

Thanks to all the people that ever contributed through code or other means such as bug reports, feature suggestions, discussions and so on.

# Install on dev enironment

# Install on dev enironment

1 Install Docker

2 Install Docker-Compose

3 Create .env file copy from config/docker/env.dist

4 Add to /etc/hosts:
 ```
127.0.0.1   campr.local
127.0.0.1   workspace1.campr.local
```  

5 In directory backend/app/config create file parametes.yml from backend/app/config/parameters.yml.onpremise

6 Add to docker-compose.yml in extra_hosts:
```
- "campr.local:127.0.0.1"
- "workspace1.campr.local:127.0.0.1"
```
 (Workspace means Time name subdomain with you have to write in etc/hosts )

7 Run container using
```
docker-compose up -d
```

8 Create new user by command 
```
docker exec -it campr_app bash
```
After entering in container Use command

```
bin/console tss:app:user-create yourname@youremail.xxx admin admin --role=ROLE_ADMIN
```

9 Mail client application: http://campr.local:1080/

After add new sub-domain workspace rebuild app in fronted folder by command
``` 
cd /app/frontend && npm install && npm run build
```


