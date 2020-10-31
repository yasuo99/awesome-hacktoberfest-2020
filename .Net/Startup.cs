using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using AutoMapper;
using Engrisk.Data;
using Engrisk.Helper;
using Engrisk.Models;
using Microsoft.AspNetCore.Authentication.JwtBearer;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Builder;
using Microsoft.AspNetCore.Hosting;
using Microsoft.AspNetCore.HttpsPolicy;
using Microsoft.AspNetCore.Identity;
using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Mvc.Authorization;
using Microsoft.EntityFrameworkCore;
using Microsoft.Extensions.Configuration;
using Microsoft.Extensions.DependencyInjection;
using Microsoft.Extensions.Hosting;
using Microsoft.Extensions.Logging;
using Microsoft.IdentityModel.Tokens;

namespace Engrisk
{
    public class Startup
    {
        public Startup(IConfiguration configuration)
        {
            Configuration = configuration;
        }

        public IConfiguration Configuration { get; }

        // This method gets called by the runtime. Use this method to add services to the container.
        // public void ConfigureDevelopmentServices(IServiceCollection services)
        // {
        //     services.AddDbContextPool<ApplicationDbContext>(options => options.UseLazyLoadingProxies().UseSqlServer(Configuration.GetConnectionString("DefaultConnection")));
        //     ConfigureServices(services);
        // }
        // public void ConfigureProductionServices(IServiceCollection services)
        // {
        //     services.AddDbContextPool<ApplicationDbContext>(options => options.UseLazyLoadingProxies().UseSqlServer(Configuration.GetConnectionString("DefaultConnection")));
        //     ConfigureServices(services);
        // }
        public void ConfigureServices(IServiceCollection services)
        {
            IdentityBuilder identityBuider = services.AddIdentityCore<Account>(opts =>
            {
                opts.Password.RequireDigit = false;
                opts.Password.RequiredLength = 8;
                opts.Password.RequireNonAlphanumeric = false;
                opts.Password.RequireUppercase = false;
            });
            identityBuider = new IdentityBuilder(identityBuider.UserType, typeof(Role), identityBuider.Services);
            identityBuider.AddEntityFrameworkStores<ApplicationDbContext>();
            identityBuider.AddRoleValidator<RoleValidator<Role>>();
            identityBuider.AddRoleManager<RoleManager<Role>>();
            identityBuider.AddSignInManager<SignInManager<Account>>();
            services.AddAuthentication(JwtBearerDefaults.AuthenticationScheme).AddJwtBearer(options =>
            {
                options.TokenValidationParameters = new Microsoft.IdentityModel.Tokens.TokenValidationParameters()
                {
                    ValidateIssuerSigningKey = true,
                    IssuerSigningKey = new SymmetricSecurityKey(Encoding.ASCII.GetBytes(Configuration.GetSection("AppSettings:TokenSecret").Value)),
                    ValidateIssuer = false,
                    ValidateAudience = false
                };
            }
            );
            
            services.AddDbContextPool<ApplicationDbContext>(options => options.UseLazyLoadingProxies().UseSqlServer(Configuration.GetConnectionString("DefaultConnection")));
            services.AddControllers(opts => {
                // var policy = new AuthorizationPolicyBuilder().RequireAuthenticatedUser().Build();
                // opts.Filters.Add(new AuthorizeFilter(policy));
            })
            .AddNewtonsoftJson(options => options.SerializerSettings.ReferenceLoopHandling = Newtonsoft.Json.ReferenceLoopHandling.Ignore);
            services.AddScoped<IAuthRepo, AuthRepo>();
            services.AddScoped<ICRUDRepo, CRUDRepo>();
            services.AddAutoMapper(typeof(AuthRepo).Assembly).AddAutoMapper(typeof(ICRUDRepo).Assembly);
            services.Configure<CloudinarySettings>(Configuration.GetSection("CloudinarySettings"));
            services.AddCors();

        }

        // This method gets called by the runtime. Use this method to configure the HTTP request pipeline.
        public void Configure(IApplicationBuilder app, IWebHostEnvironment env)
        {
            if (env.IsDevelopment())
            {
                app.UseDeveloperExceptionPage();
            }

            // app.UseHttpsRedirection();
            app.UseCors(options => options.AllowAnyOrigin().AllowAnyHeader().AllowAnyMethod());
            app.UseRouting();
            app.UseAuthentication();
            app.UseAuthorization();

            app.UseEndpoints(endpoints =>
            {
                endpoints.MapControllers();
            });
        }
    }
}
